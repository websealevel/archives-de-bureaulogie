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
    // $results_clened_clips = action_clean_clips();

    report_generate_clips_e($results_generated_clips);
    // report_clean_clips_e($results_clened_clips);

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
 * Retourne la liste des extraits vidéos supprimés car ils n'étaient pas déclarés dans le fichier source, ou leur format n'était pas valide
 * @return array
 */
function action_clean_clips(): array
{
    $cleaned = array();
    $cleaned['undeclared'] = remove_undeclared_clips();
    $cleaned['invalid'] = remove_invalid_clips();
    return $cleaned;
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
