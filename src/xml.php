<?php

/**
 * Les fonctions manipulant le fichier source en XML
 * @link
 *
 * @package wsl 
 */


require_once 'src/const.php';

/**
 * Retourne un élément enfant d'un élément par nom
 * @param DOMEelement $el L'élément dont on cherche un enfant
 * @param string $child_name Le nom de l'élément recherché
 * @throws Exception Si $child_name est vide, si l'élément n'a pas d'enfants, si un enfant n'est pas défini
 * @return DOMElement L'élément enfant dont le nom correspond
 */
function child_element_by_name(DOMElement $el, string $child_name): DOMElement
{

    if (empty($child_name))
        throw new Exception("L'élément " . $child_name . "n'existe pas.");

    if (!$el->hasChildNodes())
        throw new Exception("L'élément " . $el->nodeName . "n'as pas d'éléments enfants.");

    $childs =  $el->childNodes;

    $child = $childs->item(0);

    if (!isset($child))
        throw new Exception("Le premier enfant de " . $el->nodeName . " n'est pas défini");
    do {
        if ($child->nodeName === $child_name) {
            return $child;
        }
    } while ($child = $child->nextSibling);
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
 * Retourne le path de l'extrait à sauvegarder
 * @param DOMElement $clip
 */
function clip_path(DOMElement $clip): string
{
    $source = declared_source_of($clip);

    $source_name = basename(source_name($source), '.' . EXTENSION_SOURCE);

    $start = child_element_by_name($clip, "debut")->nodeValue;

    $end = child_element_by_name($clip, "fin")->nodeValue;

    $slug = strtolower(trim(child_element_by_name($clip, "slug")->nodeValue));

    $clip_file_name = sprintf("%s-%s-%s:%s.%s", $source_name, $slug, $start, $end, EXTENSION_CLIP);

    $clip_path = PATH_CLIPS . '/' . $clip_file_name;

    return $clip_path;
}
