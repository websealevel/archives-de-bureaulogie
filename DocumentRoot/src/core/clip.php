<?php

/**
 * Liste des fonctions générant les extraits (clips)
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/query.php';
require_once __DIR__ . '/journaling.php';


/**
 * Génére les clips déclarés dans le fichier source 
 * @param string file_source Optional. Le fichier source déclarant les extraits (au format XML) 
 * @return array La liste des extraits générés, déjà existants et invalides
 */
function generate_clips(string $file_source = SOURCE_FILE): array
{
    //On récupere les extraits déclarés
    $declared_clips = query_declared_clips($file_source);

    $results = array(
        "already_exists" => array(),
        "created" => array(),
        "invalid" => array()
    );

    foreach ($declared_clips as $clip) {

        try {

            $declared_source = declared_source_of($clip);

            //On récupere le nom de la source réeelle
            $filename_source_video = source_name($declared_source);

            //On vérifie que la source est disponible
            if (!is_source_available($filename_source_video)) {
                $message = "La source déclarée " . $filename_source_video . " n'a pas été uploadée sur le serveur. Veuillez l'uploader d'abord.";
                throw new Exception($message);
            }

            //On vérifie que les timecodes sont valides
            if (!are_timecodes_valid($clip, $filename_source_video)) {
                $clip_slug = $clip->getAttribute("slug");
                $message = "Les timescodes de l'extrait " . $clip_slug . " ne sont pas valides. Veuillez les corriger.";
                throw new Exception($message);
            }

            //On vérifie que le clip n'existe pas déjà.
            $path_clip_created = clip_path($clip);

            if (file_exists($path_clip_created)) {
                $file_name = str_replace(PATH_CLIPS . "/", "", $path_clip_created);
                $results["already_exists"][] = $file_name;
                continue;
            } else {

                //Tout est valide on peut passer à la génération du clip
                $results["created"][] = clip_source($clip, $filename_source_video);
            }
        } catch (Exception $e) {

            $results['invalid'][] = str_replace(PATH_CLIPS . "/", "", clip_path($clip));
        }
    }

    return $results;
}

/**
 * Produit un extrait de la vidéo source et retourne le path
 * @param DOMElement $clip L'élément extrait qui définit l'extrait à préparer
 * @param string $file_source Le fichier vidéo source à cliper
 * @throws Exception FFMPEG
 * @return string Le path de l'extrait crée
 */
function clip_source(DOMElement $clip, string $file_source): string
{

    $path_source = PATH_SOURCES . '/' . $file_source;

    $ffmpeg = FFMpeg\FFMpeg::create(array(
        'ffmpeg.binaries'  => $_ENV['PATH_BIN_FFMPEG'],
        'ffprobe.binaries' => $_ENV['PATH_BIN_FFPROBE'],
        'timeout'          => $_ENV['FFMPEG_TIMEOUT'],
        'ffmpeg.threads'   => $_ENV['FFMPEG_THREADS'],
    ));

    $video = $ffmpeg->open($path_source);

    $from_in_seconds = timecode_to_seconds(child_element_by_name($clip, "debut")->nodeValue);
    $to_in_seconds = timecode_to_seconds(child_element_by_name($clip, "fin")->nodeValue);

    $duration = $to_in_seconds - $from_in_seconds;

    $video_clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($from_in_seconds), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));

    $path_to_save_clip = clip_path($clip);

    $format = new FFMpeg\Format\Video\X264();

    $video_clip
        ->filters()
        ->resample(ENCODING_OPTION_AUDIO_SAMPLING_RATE);

    $format
        ->setKiloBitrate(ENCODING_OPTION_VIDEO_KBPS)
        ->setAudioKiloBitrate(ENCODING_OPTION_AUDIO_KBPS);

    $video_clip->save($format, $path_to_save_clip);

    /**
     * Normalization du son "à la main" car [pas intégrée encore dans PHP-FFMPEG](https://github.com/PHP-FFMpeg/PHP-FFMpeg/issues/328).
     * Voir la doc : https://trac.ffmpeg.org/wiki/AudioVolume
     */

    /**
     * Premiere passe : détection du volume max
     */
    $cmd_detect_max_volume = sprintf('%s -i %s -filter:a volumedetect -vn -sn -dn -f null /dev/null 2>&1 | grep max_volume', $_ENV['PATH_BIN_FFMPEG'], $path_to_save_clip);

    $output = null;
    $result_code = null;

    exec($cmd_detect_max_volume, $output, $result_code);

    $correction_dB = compute_correction_db($output);

    /**
     * Deuxième passe : application d'une correction pour arriver à un volume max à 0dB, puis enregistrement du fichier avec audio normalisé ds un fichier temporaire
     */

    $tmp_file = $path_to_save_clip . '_remastered_tmp.mp4';

    $cmd_normalise_volume_to_0_db = sprintf('%s -i %s -af "volume=%sdB" -c:v copy -c:a aac -b:a 192k %s', $_ENV['PATH_BIN_FFMPEG'], $path_to_save_clip, $correction_dB, $tmp_file);

    exec($cmd_normalise_volume_to_0_db, $output, $result_code);

    $cmd_replace_tmp_file = sprintf('cp -f %s %s', $tmp_file, $path_to_save_clip);

    $cmd_rm_tmp_file = sprintf('rm %s', $tmp_file);

    exec($cmd_replace_tmp_file, $output, $result_code);
    exec($cmd_rm_tmp_file, $output, $result_code);

    return $path_to_save_clip;
}

/**
 * Retourne la correction en dB à ajouter à l'extrait pour que le volume max soit ramené à 0dB. Correction de +5dB par défaut si une erreur se produit
 * @param array $output Le résultat de la commande avec ffmpeg et le filtre loudnorm
 * @see http://ffmpeg.org/ffmpeg-all.html#loudnorm
 */
function compute_correction_db(array $output): int
{

    if (empty($output)) {
        //Applique un db par défaut de +5dB
        return 5;
    }

    /**
     * Le volume max est renseignée sur la ligne "max_volume"
     */

    $pos = strpos($output[0], 'max_volume');

    if (false == $pos) {
        //Applique un db par défaut de +5dB
        return 5;
    }

    $sub = substr($output[0], $pos);

    $pos = strpos($sub, ':');

    if (false == $pos) {
        //Applique un db par défaut de +5dB
        return 5;
    }

    $max_dB = substr($sub, $pos + 1);

    $max_db_val = intval(trim($max_dB));

    return $max_db_val * -1;
}
