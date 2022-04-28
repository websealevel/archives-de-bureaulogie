<?php

/**
 * Les autres fonctions utils
 *
 * @package wsl 
 */

require_once 'const.php';

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
 * Supprime les sources invalides dans le path des sources
 * @return array La liste des fichiers supprimés
 */
function remove_invalid_sources(string $path = PATH_SOURCES, string $format = FORMAT_FILE_VIDEO_SOURCE): array
{
    //Appliquer une regex du format attendu des sources et supprimer les fichiers qui ne respectent pas le format

    return array();
}

/**
 * Supprime les extraits invalides dans le path des sources
 * @return array La liste des noms de fichiers supprimés
 */
function remove_invalid_clips(string $path = PATH_CLIPS, string $format = FORMAT_FILE_VIDEO_CLIP): array
{
    //Appliquer une regex du format attendu des extraits et supprimer les fichiers qui ne respectent pas le format

    return array();
}

/**
 * Supprime les extraits qui ne sont pas déclarés dans le fichier source
 * @param string $file_source Optional Default SOURCE_FILE
 * @return array La liste des noms de fichiers supprimés
 */
function remove_undeclared_clips(string $file_source = SOURCE_FILE): array
{
    return array();
}

/**
 * Supprime une source sur le PATH_SOURCE. Renvoie vrai si la suppression a réussi, faux sinon
 * @param string $file_name Le nom du fichier source à supprimer
 * @return bool
 */
function remove_source(string $file_name): bool
{
    return false;
}

/**
 * Supprime un extrait sur le PATH_CLIP. Renvoie vrai si la suppression a réussi, faux sinon
 * @param string $file_name Le nom du fichier extrait à supprimer
 * @return bool
 */
function remove_clip(string $file_name): bool
{
    return false;
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

