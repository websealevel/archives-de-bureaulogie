<?php

/**
 * Parse le fichier source extraits.xml pour éditer les extraits
 *
 * @package wsl 
 */

require 'vendor/autoload.php';
require 'src/utils.php';

//Global Exception handler
set_exception_handler(function ($e) {
    echo 'Oups, il y a eu un problème :/' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    error_log($e);
    die;
});
//Global Error handler
ini_set('error_log', 'journal.log');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    //Convert errors included inuour error-reporing setting into Exceptions
    if (!(error_reporting() & $errno)) {
        return;
    }

    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});


//Configurer la gestion des erreurs : tout doit aller dans un fichier log une fois en prod


echo "Génération des extraits vidéos" . PHP_EOL;

$file_source = 'extraits.xml';

if (!is_source_file_valid($file_source)) {
    echo 'Le fichier source ' . $file_source . ' est invalide. Veuillez le corriger.';
    die;
}

echo 'Le fichier source ' . $file_source . ' est valide.' . PHP_EOL;

echo 'Génération des extraits' . PHP_EOL;

generate_clips($file_source);

echo 'Suppresion des extraits non déclarés' . PHP_EOL;

remove_untracked_clips($file_source);

echo 'Les extraits ont été ajoutés/supprimés. La liste est à jour avec la base de données.' . PHP_EOL;



//Restore default error handler.
restore_error_handler();
//Restore default exception handler.
restore_exception_handler();
