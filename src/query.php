<?php

/**
 * Liste toutes les requêtes XPATH sur le fichier source
 *
 * @package wsl 
 */

/**
 * Retourne la liste des sources déclarées
 * @param string $file_source Optional. Le fichier source
 * @return DOMNodeList
 */
function query_declared_sources(string $file_source = SOURCE_FILE): DOMNodeList
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $result = $xpath->query('//ns:extraits/ns:source');

    return $result;
}


/**
 * Retourne la liste des extraits déclarés dans le dossier
 * @param string $file_source Optional. Le fichier source
 * @return DOMNodeList
 */
function query_declared_clips(string $file_source = SOURCE_FILE)
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $result = $xpath->query('//ns:extrait');

    return $result;
}
