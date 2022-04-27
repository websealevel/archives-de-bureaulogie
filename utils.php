<?php

/**
 * Toutes les fonctions utiles
 *
 * @package wsl 
 */


require 'const.php';
require 'query.php';

/**
 * Retourne vrai si le fichier source est valide (validation via le DTD), faux sinon
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return bool
 */
function is_source_valid(string $file_source = SOURCE_FILE): bool
{
    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    return $dom->validate();
}

/**
 * Génére les clips déclarés dans le fichier source 
 * @param string file_source Optional. Le fichier source déclarant les extraits (au format XML) 
 * @return void
 */
function generate_clips(string $file_source = SOURCE_FILE)
{

    $sources = query_declared_sources();
    $clips = query_declared_clips();

    echo "Nombre de sources déclarées : " . $sources->count() . PHP_EOL;
    echo "Nombre d'extraits déclarés : " . $clips->count() . PHP_EOL;
}


function remove_untracked_clips(string $file_source = SOURCE_FILE)
{
}

/** 
 * Retourne le fichier XML sous forme de DOM s'il est valide. 
 * @param string $file_source Optional. Le fichier source  
 * @throws Exception Si le schéma n'est pas valide 
 * @return DOMDocument Abstraction du fichier sous forme de DOM */
function load_xml(string $file_source = SOURCE_FILE): DOMDocument
{

    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    if (!$dom->validate()) {
        throw new \Exception('Fichier source ' . $file_source . 'invalide. Impossible de le charger. Vérifiez l\'intégrité du fichier avant de continuer.');
    }

    return $dom;
}

/**
 * Retourne le fichier source sous forme de wrapper xpath prêt à être requêter
 * @param string $file_source Optional. Le fichier source
 * @param string $namespace Optional. Le namespace du fichier source XML
 * @return DOMXPath
 */
function load_xpath(string $file_source = SOURCE_FILE, string $namespace = XMLNS_SOURCE_FILE): DOMXPath
{
    $dom = load_xml($file_source);
    $xpath = new DOMXpath($dom);
    $xpath->registerNamespace('ns', $namespace);
    return $xpath;
}
