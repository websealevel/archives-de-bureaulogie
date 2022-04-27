<?php

/**
 * Les fonctions rattachées à des actions utilisateurs
 *
 * @package wsl 
 */

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

    echo 'Les extraits manquants ont été crées. La liste des extraits est à jour avec la base de données.' . PHP_EOL;


    $already_exists = array_filter($results, function ($key) {
        return 'already_exists' === $key;
    }, ARRAY_FILTER_USE_KEY);

    $created = array_filter($results, function ($key) {
        return 'created' === $key;
    }, ARRAY_FILTER_USE_KEY);

    echo "Liste des extraits crées: " . PHP_EOL;
    foreach ($created as $clip) {
        echo $clip . PHP_EOL;
    }
    die;

    echo "Liste des extraits déjà crées: " . PHP_EOL;
    foreach ($already_exists as $clip) {
        echo $clip . PHP_EOL;
    }
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
