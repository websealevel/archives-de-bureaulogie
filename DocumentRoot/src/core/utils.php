<?php

/**
 * Les autres fonctions utils
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/../models/Clip.php';
require_once __DIR__ . '/../models/ClipMetaData.php';

/**
 * Retourne le timecode valide en secondes (milisecondes incluses en décimal)
 * @param string $timecode
 * @return float Temps en secondes
 */
function timecode_to_seconds(string $timecode): float
{
    if (!is_timecode_format_valid($timecode)) {
        throw new Exception("Le format du timecode " . $timecode . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    $hour = intval(substr($timecode, 0, 2));
    $minute = intval(substr($timecode, 3, 2));
    $second = intval(substr($timecode, 6, 2));
    $milisecond = intval(substr($timecode, 9, 3));

    $time_in_seconds = $hour * 3600 + $minute * 60 + $second + $milisecond / 1000;

    return $time_in_seconds;
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
 * Retourne le path (dont le nom du fichier) de l'extrait à sauvegarder.
 * Le nom du fichier de l'extrait est construit à partir du slug et des timescodes.
 * @param DOMElement $clip
 */
function clip_path(DOMElement $clip): string
{

    $clip_file_name = format_to_clip_file($clip);

    $clip_path = PATH_CLIPS . '/' . $clip_file_name;

    return $clip_path;
}

/**
 * Retourne un nom de fichier pour le clip au format FORMAT_FILE_VIDEO_CLIP
 * @see FORMAT_FILE_VIDEO_CLIP
 * @param DOMElement $clip
 * @return string
 */
function format_to_clip_file(DOMElement $clip): string
{

    $source = declared_source_of($clip);

    $source_name = basename(source_name($source), '.' . EXTENSION_SOURCE);

    $start = child_element_by_name($clip, "debut")->nodeValue;

    $end = child_element_by_name($clip, "fin")->nodeValue;

    $slug = strtolower(trim(child_element_by_name($clip, "slug")->nodeValue));

    return sprintf("[%s][%s][%s][%s].%s", $source_name, $slug, $start, $end, EXTENSION_CLIP);
}


/**
 * Retourne une chaine de caractères formatté au format FORMAT_FILE_VIDEO_SOURCE (utilisé notamment pour générér les noms des fichiers vidéos sources)
 * @see string FORMAT_FILE_VIDEO_SOURCE
 * @param DownloadRequest $download_request
 * @return string Le nom du fichier vidéo source au format correct
 * @throws Exception - Si le nom du fichier source n'est pas validé par le format attendu
 */
function format_to_source_file(DownloadRequest $download_request): string
{
    $series_name = $download_request->series_name;
    $id = $download_request->id;

    $series_name = preg_replace('#[ -]+#', '-', $series_name);
    $id = preg_replace('#[ -]+#', '-', $id);

    $series_name_snake_case = strtolower($series_name);
    $id_snake_case = strtolower($id);

    $file_name = sprintf("%s-%s.%s", $series_name_snake_case, $id_snake_case, EXTENSION_SOURCE);

    if (!preg_match('/^' . FORMAT_FILE_VIDEO_SOURCE . '$/', $file_name)) {
        throw new Exception("Impossible de valider le nom de la source à télécharger: " . $file_name);
    }
    return $file_name;
}

/**
 * Retourne le nom de la source à partir du nom du fichier extrait, faux si une erreur se produit (format non valide)
 * @param string $filename Le nom du fichier extrait
 * @return ClipMetaData|false
 * @throws Exception - S'il n'y a pas autant de métadonnées que dans le model ClipMetadata
 * @see src/models/ClipMetadata.php
 */
function extract_metadata($filename): ClipMetaData|false
{
    if (!is_clip_filename_valid_format($filename))
        return false;

    $basename = basename($filename, '.' . EXTENSION_CLIP);

    $metadata = array();

    do {
        $iter_first = strpos($basename, '[');
        $iter_end = strpos($basename, ']');
        $metadata[] = mb_substr($basename, $iter_first, $iter_end - $iter_first + 1);
        $basename = str_replace($metadata[count($metadata) - 1], '', $basename);
    } while (!empty($basename));

    $values =  array_map(function ($data) {
        return str_replace(array('[', ']'), '', $data);
    }, $metadata);

    if (4 !== count($values)) {
        throw new Exception("Toutes les métadonnées de l'extrait n'ont pas pu être retrouvées.");
    }

    return new ClipMetaData(
        source: $metadata[0],
        slug: $metadata[1],
        timecode_start: $metadata[2],
        timecode_end: $metadata[3],
    );
}
