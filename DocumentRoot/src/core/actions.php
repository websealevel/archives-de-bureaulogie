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

/**
 * Met à jour la base de données des clips (ajoute, supprime en fonction du fichier source), nettoie les clips invalides
 * @return bool
 */
function action_update_clips()
{
    if (!is_source_file_valid()) {
        throw new Exception("Le fichier source est invalide. Veuillez le corriger d'abord.");
    }

    printf("Génération des clips...\n");

    $results = generate_clips();

    generate_clips_report_e($results);
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

function action_clean_clips()
{
    // remove_undeclared_clips();
    // remove_invalid_clips();
}

function action_clean_sources()
{
    // remove_invalid_sources();
}