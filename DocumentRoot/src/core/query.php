<?php

/**
 * Liste toutes les requêtes XPATH sur le fichier source
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/xml.php';
require_once __DIR__ . '/validation.php';

/**
 * Retourne la liste des sources déclarées
 * @param string $file_source Optional. Le fichier source
 * @return DOMNodeList
 */
function query_declared_sources(string $file_source = SOURCE_FILE): DOMNodeList
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $sources = $xpath->query('//ns:extraits/ns:source');

    if (!$sources)
        return new DOMNodeList();

    //Check que les sources déclarées correspondent aux sources présentes
    $not_found = array_filter(iterator_to_array($sources), function ($source) {
        return !is_source_available($source->getAttribute('name'));
    });

    dd($not_found);

    return $sources;
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

/**
 * Retourne la liste des vidéos sources présentes dans PATH_SOURCES
 * @param string $file_source Optional. Le fichier source
 * @param string $path Optional. Le PATH des fichiers sources
 */
function query_sources(string $file_source = SOURCE_FILE, string $path = PATH_SOURCES, $extension = EXTENSION_SOURCE)
{
    $pattern = $path . '/*.' . $extension;
    $arrFiles = glob($pattern);
    return $arrFiles;
}

/**
 * Retourne la liste des extraits présents dans PATH_CLIPS
 * @param string $file_source Optional. Le fichier source
 * @param string $path Optional. Le PATH des fichiers sources
 */
function query_clips(string $file_source = SOURCE_FILE, string $path = PATH_CLIPS, $extension = EXTENSION_CLIP)
{
    $pattern = $path . '/*.' . $extension;
    $arrFiles = glob($pattern);
    return $arrFiles;
}
