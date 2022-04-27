<?php

/**
 * Liste toutes les requêtes XPATH sur le fichier source
 *
 * @package wsl 
 */

/**
 * Retourne la liste des sources déclarées
 * @param string $file_source Optional. Le fichier source
 * @param string $PATH Optional. Le PATH des fichiers sources
 */
function query_declared_sources(string $file_source = SOURCE_FILE,  string $PATH = PATH_SOURCES): DOMNodeList
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $result = $xpath->query('//ns:extraits/ns:source');

    return $result;
}


/**
 * Retourne la liste des extraits déclarés dans le dossier
 * @param string $file_source Optional. Le fichier source
 * @param string $PATH Optional. Le PATH des fichiers sources
 * @return DOMNodeList
 */
function query_declared_clips(string $file_source = SOURCE_FILE,  string $PATH = PATH_SOURCES)
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $sources = query_declared_sources($file_source, $PATH);

    $node = $sources->item(0);
    if (!isset($node))
        return;
    do {
        echo "name= " . $node->getAttribute('name') . PHP_EOL;
    } while ($node = $node->nextSibling);

    return;
}
