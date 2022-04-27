<?php
/**
 * Parse le fichier source extraits.xml pour éditer les extraits
 *
 * @package wsl 
 */

require 'utils.php';

//Global Exception handler
set_exception_handler(function (Exception $e) {
    echo 'Oups, il y a eu un problème :/' . PHP_EOL;
    error_log($e);
    die;
});
//Global Error handler
ini_set('error_log', 'error_log');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {

    //Convert errors included inuour error-reporing setting into Exceptions
    if (!(error_reporting()& $errno)) {
        return;
    }

    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});


//Configurer la gestion des erreurs : tout doit aller dans un fichier log une fois en prod


echo "Génération des extraits vidéos" . PHP_EOL;

$file_source = 'extraits.xml';

if (!is_source_valid($file_source)) {
    echo 'Le fichier source ' . $file_source . ' est invalide. Veuillez le corriger.';
    die;
}

echo 'Le fichier source ' . $file_source . ' est valide.';

echo 'Génération des extraits' . PHP_EOL;


//Restore default exception handler.
restore_exception_handler();
//Restore default error handler.
restore_error_handler();