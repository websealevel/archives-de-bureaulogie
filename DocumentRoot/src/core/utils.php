<?php

/**
 * Les autres fonctions utils
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';

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
 * Retourne une chaine de caractères formatté au format Source
 * @see string FORMAT_FILE_VIDEO_SOURCE
 */
function format_to_source_file(DownloadRequest $download_request)
{

    $series_name = $download_request->series_name;
    $id = $download_request->id;

    $series_name = preg_replace('#[ -]+#', '-', $series_name);
    $id = preg_replace('#[ -]+#', '-', $id);

    $series_name_snake_case = strtolower($series_name);
    $id_snake_case = strtolower($id);

    $file_name = sprintf("%s--%s.%s", $series_name_snake_case, $id_snake_case, EXTENSION_SOURCE);

    if (!preg_match('/' . FORMAT_FILE_VIDEO_SOURCE . '/', $file_name)) {
        throw new Exception("Impossible de valider le nom de la source à télécharger: " . $file_name);
    }
    return $file_name;
}
