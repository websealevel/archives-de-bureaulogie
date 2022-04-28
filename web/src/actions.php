<?php

/**
 * Les fonctions rattachées à des actions utilisateurs
 *
 * @package wsl 
 */

require_once 'utils.php';
require_once 'clip.php';
require_once 'validation.php';
require_once 'download.php';

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

/**
 * Télécharge une nouvelle vidéo source sur le serveur
 */
function action_download_video(DownloadRequest $download_request, string $path_to_download = PATH_SOURCES): SplFileInfo
{
    $file = download($download_request);

    return $file;
}

/**
 * Valide et déplace une vidéo téléchargée vers le dossier sources
 * @param string $path_to_download Le fichier téléchargé
 */
function action_make_source_of_download(string $path_to_download): bool
{



    return false;
}
