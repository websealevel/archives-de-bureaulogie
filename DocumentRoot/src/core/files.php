<?php

/**
 * Liste des fonctions manipulant les fichiers
 *
 * @package wsl 
 */

/**
 * Retourne la liste des fichiers et des dossiers dans un dossier
 * @param string $path Le path du dossier à scanner
 * @param array $files_to_exclude. Optional. Default array(). Une liste de fichiers à exclure du scan
 * @return array
 */
function list_files_in_dir(string $path, array $files_to_exclude = array()): array
{
    //On récupere la liste des extraits
    $files = scandir($path);
    if (false === $files)
        return array();

    return array_diff($files, $files_to_exclude);
}

/**
 * Retourne vrai si le fichier de la vidéo source existe, faux sinon
 * @param string $source_name Le nom de la source
 * @return bool
 */
function source_exists(string $source_name): bool
{
    $path_source = PATH_SOURCES . '/' . $source_name;
    return file_exists($path_source);
}

/**
 * Supprime un fichier, renvoie vrai si la suppression a réussi, faux sinon
 * @param string $file_name Le nom du fichier à supprimer
 * @return bool
 */
function delete_file(string $file_name): bool
{
    return false;
}

/**
 * Produit un extrait de la vidéo source et retourne le path
 * @param DOMElement $clip L'élément extrait qui définit l'extrait à préparer
 * @param string $file_source Le fichier source à cliper
 * @throws Exception FFMPEG
 * @return string Le path de l'extrait crée
 */
function clip_source(DOMElement $clip, string $file_source = SOURCE_FILE): string
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

    $video_clip->filters()->resample(ENCODING_OPTION_AUDIO_SAMPLING_RATE);

    $format
        ->setKiloBitrate(ENCODING_OPTION_VIDEO_KBPS)
        ->setAudioKiloBitrate(ENCODING_OPTION_AUDIO_KBPS);

    $video_clip->save($format, $path_to_save_clip);

    return $path_to_save_clip;
}
