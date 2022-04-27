<?php

/**
 * Parse le fichier source extraits.xml pour éditer les extraits
 *
 * @package wsl 
 */

require_once 'vendor/autoload.php';
require_once 'src/utils.php';
require_once 'src/ffmpeg.php';
require_once 'src/validation.php';


if (!is_source_file_valid()) {
    echo 'Le fichier source ' . SOURCE_FILE . ' est invalide. Veuillez le corriger.';
    die;
}

echo 'Le fichier source ' . SOURCE_FILE . ' est valide.' . PHP_EOL;

echo 'Génération des extraits' . PHP_EOL;

generate_clips();

echo 'Suppresion des extraits non déclarés' . PHP_EOL;

remove_untracked_clips();

echo 'Les extraits ont été ajoutés/supprimés. La liste est à jour avec la base de données.' . PHP_EOL;



//Restore default error handler.
restore_error_handler();
//Restore default exception handler.
restore_exception_handler();
