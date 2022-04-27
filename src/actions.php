<?php

/**
 * Les fonctions rattachées à des actions utilisateurs
 *
 * @package wsl 
 */

require_once 'src/utils.php';
require_once 'src/ffmpeg.php';
require_once 'src/validation.php';
require_once 'src/actions.php';
require_once 'src/handlers.php';

/**
 * Met à jour la base de données des clips (ajoute, supprime en fonction du fichier source), nettoie les clips invalides
 * @return bool
 */
function action_update_clips()
{
    if (!is_source_file_valid()) {
        throw new Exception("Le fichier source est invalide. Veuillez le corriger d'abord.");
    }

    $results = generate_clips();

    generate_clips_report_e($results);
}

/**
 * Supprime les clips invalides/non déclarés dans le fichier source et les sources invalides
 * @return void
 */
function action_clean()
{
    action_clean_sources();
    action_clean_clips();
}

function action_clean_clips()
{
    // remove_undeclared_clips();
    // remove_invalid_clips();
}

function action_clean_sources()
{
    // remove_invalid_sources();
}
