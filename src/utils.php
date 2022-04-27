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
function is_source_file_valid(string $file_source = SOURCE_FILE): bool
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
    //On récupere les extraits déclarés
    $clips = query_declared_clips($file_source);

    foreach ($clips as $clip) {

        $declared_source = declared_source_of($clip);

        //On récupere le nom de la source réeelle
        $filename_source_video = source_name($declared_source);

        //On vérifie que la source est disponible
        if (!is_source_available($filename_source_video)) {
            $message = "La source déclarée " . $filename_source_video . " n'a pas été uploadée sur le serveur. Veuillez l'uploader d'abord.";
            throw new Exception($message);
        }

        //La source est disponible. On peut passer à la génération du clip

        //On vérifie que les timecodes sont valides
        if (!are_timecodes_valid($clip, $filename_source_video)) {
            $clip_slug = $clip->getAttribute("slug");
            $message = "Les timescodes de l'extrait " . $clip_slug . " ne sont pas valides. Veuillez les corriger.";
            throw new Exception($message);
        }
    }
}

/**
 * Retourne l'élément source de l'extrait
 * @param DOMElement $clip  Optional. L'élément clip 
 * @return DOMElement L'élément source parent
 */
function declared_source_of(DOMElement $clip): DOMElement
{
    return $clip->parentNode;
}

/**
 * Retourne le nom (attribut) de la source déclarée
 * @param DOMElement $source
 * @return string
 */
function source_name(DOMElement $source): string
{
    return $source->getAttribute('name');
}

/**
 * Retourne vrai si les timecodes sont valides (format, min, max), faux sinon
 * @param DOMElement $clip L'extrait dont les timescodes sont à valider
 * @param string $file_source Le path du fichier source de l'extrait
 * @return bool
 */
function are_timecodes_valid(DOMElement $clip, string $file_source): bool
{
    $start = $clip->getAttribute("debut");
    $end = $clip->getAttribute("fin");

    var_dump($start, $end);

    return true;
}

/**
 * Retourne vrai si la source existe sur la PATH_SOURCES ET qu'elle est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_SOURCES Optional. Le path des sources
 * @return bool
 */
function is_source_available(string $file_name = SOURCE_FILE, $path = PATH_SOURCES)
{
    if (!is_source_valid($file_name, $path))
        return false;

    $file_path = $path . '/' . $file_name;

    return file_exists($file_path);
}

/**
 * Retourne vrai si le clip existe sur la PATH_CLIPS ET qu'il est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_CLIPS Optional. Le path des extraits
 * @return bool
 */
function is_clip_available(string $file_name = SOURCE_FILE, $path = PATH_CLIPS)
{
    if (!is_clip_valid($file_name, $path))
        return false;

    $file_path = $path . '/' . $file_name;

    return file_exists($file_path);
}

/**
 * Retourne vrai si le fichier de la source (vidéo) est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_SOURCES Optional. Le path des sources
 * @return bool
 */
function is_source_valid(string $file_name, $path = PATH_SOURCES)
{

    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_SOURCE) {

        $message = sprintf("La source %s n'a pas un format valide", $file_name);
        throw new Exception($message);
    }

    return true;
}

/**
 * Retourne vrai si le clip est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_CLIPS Optional. Le path des extraits
 * @return bool
 */
function is_clip_valid(string $file_name = SOURCE_FILE, $path = PATH_CLIPS)
{
    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_CLIP) {

        $message = sprintf("L'extrait %s n'a pas un format valide", $file_name);
        throw new Exception($message);
    }

    return true;
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
