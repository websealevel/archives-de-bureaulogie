<?php
/**
 * Parse le fichier source extraits.xml pour éditer les extraits
 *
 * @package wsl 
 */

require 'utils.php';

echo "Génération des extraits vidéos" . PHP_EOL;

$file_source = 'extraits.xml';

if (is_source_valid($file_source)) {
    echo 'Le fichier source ' . $file_source . ' est valide.';
}
else {
    echo 'Le fichier source ' . $file_source . ' est invalide. Veuillez le corriger.';
    die;
}