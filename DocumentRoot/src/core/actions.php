<?php

/**
 * Les fonctions rattachées à des actions utilisateurs
 *
 * @package wsl 
 */

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/clip.php';
require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/download.php';
require_once __DIR__ . '/../models/ClipMetaData.php';

// Corriger cette dependence (core ne doit pas dépendre de web)
require_once __DIR__ . '/../web/environment.php';

load_env();

/**
 * Met à jour la base de données des clips (ajoute et nettoie des clips en fonction du fichier source)
 * @return bool
 */
function action_update_clips(): bool
{
    if (!is_source_file_valid()) {
        throw new Exception("Le fichier source est invalide. Veuillez le corriger d'abord.");
    }

    $results_generated_clips = generate_clips();
    // $results_cleaned_clips = action_clean_clips();

    report_generate_clips_e($results_generated_clips);
    // report_clean_clips_e($results_cleaned_clips);

    return true;
}

/**
 * Supprime les clips invalides(non déclarés dans le fichier source, format invalide) et les sources invalides(format invalide)
 * @return void
 */
function action_clean()
{
    action_clean_sources();
    action_clean_clips();
}

/**
 * Retourne la liste des extraits vidéos supprimés car ils n'étaient pas déclarés dans le fichier source, ou ils n'étaient pas valides.
 * @see function remove_invalid_clips()
 * @return array
 */
function action_clean_clips(): array
{
    return remove_invalid_clips();
}

/**
 * Retourne la liste des vidéos sources supprimées car elles n'étaient pas déclarées dans le fichier source, ou leur format n'était pas valide
 * @return array
 */
function action_clean_sources(): array
{
    $cleaned = array();
    $cleaned['undeclared'] = remove_undeclared_sources();
    $cleaned['invalid'] = remove_invalid_sources();
    return $cleaned;
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
 * Supprime les extraits non déclarés ou invalides dans le dossier des extraits
 * @param string $path Le path des extraits (dossier)
 * @return array La liste des extraits qui ont été supprimés
 */
function remove_invalid_clips(string $path = PATH_CLIPS): array
{
    $invalid = array();

    $files = list_files_in_dir($path, array(
        '.',
        '..',
        'README.md',
        'index.php'
    ));

    $invalid = array_filter($files, function ($file) {

        $is_valid = false;

        //le nom du fichier doit respecter le format sinon ça dégage 
        if (!clip_has_valid_filename_format($file))
            return true;

        //S'il respecte le format on peut analyser le nom pour récupérer : source, slug et les timecodes

        //Retrouver le clip s'il est déclaré
        $metadata = extract_metadata($file);

        var_dump($metadata);
        die;

        $clip = query_clip($metadata->source, $metadata->slug, $metadata->timecode_start, $metadata->timecode_end, 'model');

        //S'il est pas déclaré => invalide il dégage

        //On retrouve sa source, si elle est pas déclarée => orphelin il degage

        //si are_timecodes_valid non => ca dégage



        // $is_valid = is_clip_valid($file) && are_timecodes_valid()

        return !$is_valid;
    });

    var_dump($invalid);
    die;

    return $invalid;
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
 * Supprime les sources qui ne sont pas déclarées dans le fichier source
 * @param string $file_source Optional Default SOURCE_FILE
 * @return array La liste des noms de fichiers supprimés
 */
function remove_undeclared_sources(string $file_source = SOURCE_FILE): array
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
