<?php

/**
 * Toutes les fonctions utiles
 *
 * @package wsl 
 */


require 'src/const.php';
require 'src/query.php';

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
 * Retourne un rapport sur l'état des sources/extraits déclarés et présents sur le serveur
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return array Rapport
 */
function report(string $file_source = SOURCE_FILE): array
{
    $declared_sources = query_declared_sources($file_source);
    $declared_clips = query_declared_clips($file_source);
    $sources = query_sources();
    $clips = query_clips();

    return array(
        'declared' => array(
            'sources' => array(
                'quantity' => 'Nombre de sources déclarées : ' . $declared_sources->count(),
                'list' => iterator_to_array($declared_sources)
            ),
            'clips' => array(
                'quantity' => 'Nombre d\'extraits déclarés :' . $declared_clips->count(),
                'list' => iterator_to_array($declared_clips)
            )
        ),
        'files' => array(
            'sources' => array(
                'quantity' => 'Nombre de sources :' . count($sources),
                'list' => $sources
            ),
            'clips' => array(
                'quantity' => 'Nombre d\'extraits :' . count($clips),
                'list' => $clips
            )
        )
    );
}


/**
 * Génére les clips déclarés dans le fichier source 
 * @param string file_source Optional. Le fichier source déclarant les extraits (au format XML) 
 * @return void
 */
function generate_clips(string $file_source = SOURCE_FILE)
{

    $report = report();

    var_dump($report);
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

/**
 * Supprime les sources invalides dans le path des sources
 * @return void
 */
function remove_invalid_sources(string $path = PATH_SOURCES, string $format): void
{
    //Appliquer une regex du format attendu des sources et supprimer les fichiers qui ne respectent pas le format
}

/**
 * Supprime les extraits invalides dans le path des sources
 * @return void
 */
function remove_invalid_clips(string $path = PATH_CLIPS, string $format)
{
    //Appliquer une regex du format attendu des extraits et supprimer les fichiers qui ne respectent pas le format

}

function remove_source(string $file_name)
{
}

function remove_clip(string $file_name)
{
}

function delete_file(string $file_name): bool
{

    return true;
}

function remove_untracked_clips(string $file_source = SOURCE_FILE)
{
}
