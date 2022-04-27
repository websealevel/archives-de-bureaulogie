<?php

/**
 * Toutes les fonctions utiles
 *
 * @package wsl 
 */

require_once 'src/const.php';

/**
 * Retourne le timecode valide en secondes. Ignore les milisecondes !
 * @param string $timecode
 * @return int secondes
 */
function timecode_to_seconds(string $timecode): int
{
    if (!is_timecode_format_valid($timecode)) {
        throw new Exception("Le format du timecode " . $timecode . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }
    return strtotime("1970-01-01 $timecode UTC");
}

/**
 * Supprime les sources invalides dans le path des sources
 * @return void
 */
function remove_invalid_sources(string $path = PATH_SOURCES, string $format): void
{
    //Appliquer une regex du format attendu des sources et supprimer les fichiers qui ne respectent pas le format
}

/**
 * Supprime les extraits invalides dans le path des sources
 * @return void
 */
function remove_invalid_clips(string $path = PATH_CLIPS, string $format)
{
    //Appliquer une regex du format attendu des extraits et supprimer les fichiers qui ne respectent pas le format

}

function remove_source(string $file_name)
{
}

function remove_clip(string $file_name)
{
}

function delete_file(string $file_name): bool
{
    return true;
}

function remove_untracked_clips(string $file_source = SOURCE_FILE)
{
}
