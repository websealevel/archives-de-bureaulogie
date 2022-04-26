<?php
/**
 * Toutes les fonctions utiles
 *
 * @package wsl 
 */


/**
 * Retourne vrai si le fichier source est valide (validation via le DTD), faux sinon
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return bool
 */
function is_source_valid(string $file_source = 'extraits.xml'): bool
{
    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    return $dom->validate();
}