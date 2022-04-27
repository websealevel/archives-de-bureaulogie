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

    $start = child_element_by_name($clip, "debut")->nodeValue;
    $end = child_element_by_name($clip, "fin")->nodeValue;

    if (!is_timecode_format_valid($start)) {
        throw new Exception("Le format du timecode de début de l'extrait " . $clip->getAttribute("slug") . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    if (!is_timecode_format_valid($end)) {
        throw new Exception("Le format du timecode de fin de l'extrait " . $clip->getAttribute("slug") . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    //Checker que end > fin
    if (!is_start_timecode_smaller_than_end_timecode($start, $end)) {
        throw new Exception("Les timecodes de début et de fin de l'extrait " . $clip->getAttribute("slug") . " ne sont pas valides. Le timecode de début doit être plus petit que le timecode de fin (doit référencer un moment antérieur dans la vidéo). Veuillez le corriger (voir la documentation).");
    }

    return true;
}

/**
 * Retourne vrai si le timecode du début est plus petit que le timecode de fin, faux sinon
 */
function is_start_timecode_smaller_than_end_timecode(string $start, string $end): bool
{
    return true;
}


/**
 * Retourne vrai si les timecodes sont dans les limites de la durée de la vidéo (ie debut plus grand que 0 et fin plus petit que durée de la vidéo ), faux sinon
 * @param string $start timecode du début
 * @param string $end timecode de fin
 * @param string $file_source Le path de la vidéo source
 * @return bool
 */
function are_timecodes_within_bounds(string $start, string $end, string $file_source): bool
{

    return true;
}

/**
 * Retourne vrai si le format d'un timecode est valide, faux sinon
 * @param string $timecode Le timecode
 * @param string $format Le format attendu pour l'expression régulière
 * @return bool
 */
function is_timecode_format_valid(string $timecode, string $format = FORMAT_TIMECODE): bool
{
    $pattern = '/' . $format . '/i';
    return boolval(preg_match($pattern, $timecode));
}

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
