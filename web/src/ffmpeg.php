<?php

/**
 * Liste des fonctions manipulant les media avec ffmpeg, génération des extraits etc...
 *
 * @package wsl 
 */

require_once 'const.php';
require_once 'query.php';
require_once 'journaling.php';


/**
 * Génére les clips déclarés dans le fichier source 
 * @param string file_source Optional. Le fichier source déclarant les extraits (au format XML) 
 * @return array La liste des extraits générés
 */
function generate_clips(string $file_source = SOURCE_FILE): array
{
    //On récupere les extraits déclarés
    $declared_clips = query_declared_clips($file_source);


    $results = array(
        "already_exists" => array(),
        "created" => array()
    );

    foreach ($declared_clips as $clip) {

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
    }

    // $report = report_clip_generation();
    // log_clip_generation($report);

    return $results;
}




/**
 * Produit un extrait de la vidéo source et retourne le path
 * @param DOMElement $clip L'élément extrait qui définit l'extrait à préparer
 * @param string $file_source Le fichier source à cliper
 * @throws Exception FFMPEG
 * @return string Le path de l'extrait crée
 */
function clip_source(DOMElement $clip, string $file_source): string
{
    $path_source = PATH_SOURCES . '/' . $file_source;
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($path_source);

    $from_in_seconds = timecode_to_seconds(child_element_by_name($clip, "debut")->nodeValue);
    $to_in_seconds = timecode_to_seconds(child_element_by_name($clip, "fin")->nodeValue);

    $duration = $to_in_seconds - $from_in_seconds;

    $video_clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds($from_in_seconds), FFMpeg\Coordinate\TimeCode::fromSeconds($duration));

    $path_to_save_clip = clip_path($clip);

    $format = new FFMpeg\Format\Video\X264();

    $video_clip->filters()->resample(ENCODING_OPTION_AUDIO_SAMPLING_RATE);

    $format
        ->setKiloBitrate(ENCODING_OPTION_VIDEO_KBPS)
        ->setAudioKiloBitrate(ENCODING_OPTION_AUDIO_KBPS);


    $video_clip->save($format, $path_to_save_clip);

    return $path_to_save_clip;
}
