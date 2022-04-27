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
    die;
});
//Global Error handler
ini_set('error_log', 'error_log');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo 'Oups, il y a eu un problème :/' . PHP_EOL;
    $error = sprintf("%s %s %s %s", $errno, $errstr, $errfile, $errline);
    error_log($error);
    die;
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



restore_exception_handler();