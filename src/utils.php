<?php

/**
 * Les autres fonctions utils
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
