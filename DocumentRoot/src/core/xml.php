<?php

/**
 * Les fonctions manipulant LE fichier source en XML
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/utils.php';

/**
 * Retourne vrai si le fichier source est valide (validation via le DTD), faux sinon
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return bool
 */
function is_source_file_valid(string $file_source = SOURCE_FILE): bool
{
    //Check que le fichier source existe.
    if (!file_exists($file_source)) {

        throw new Exception("Le fichier source n'existe pas. Veuillez créer le fichier " . SOURCE_FILE . " pour déclarer les extraits que vous souhaitez générer.");

        return false;
    }

    //Check que le fichier source a un schéma valide spéificié par le dtd.
    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    return $dom->validate();
}

/** 
 * Retourne le fichier XML sous forme de DOM s'il est valide. 
 * @param string $file_source Optional. Le fichier source  
 * @throws Exception - Si le schéma n'est pas valide 
 * @return DOMDocument Abstraction du fichier sous forme de DOM */
function load_xml(string $file_source = SOURCE_FILE): DOMDocument
{

    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    if (!$dom->validate()) {
        throw new Exception('Fichier source ' . $file_source . 'invalide. Impossible de le charger. Vérifiez l\'intégrité du fichier avant de continuer.');
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
