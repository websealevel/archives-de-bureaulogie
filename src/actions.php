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

    $clips_generated = generate_clips();

    // remove_undeclared_clips();

    // remove_invalid_clips();

    echo 'Les extraits ont été ajoutés/supprimés. La liste est à jour avec la base de données.' . PHP_EOL;
}
