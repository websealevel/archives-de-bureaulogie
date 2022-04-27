<?php

/**
 * Toutes les fonctions utiles
 *
 * @package wsl 
 */


require 'src/const.php';
require 'src/validation.php';
require 'src/query.php';



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

        //On vérifie que les timecodes sont valides
        if (!are_timecodes_valid($clip, $filename_source_video)) {
            $clip_slug = $clip->getAttribute("slug");
            $message = "Les timescodes de l'extrait " . $clip_slug . " ne sont pas valides. Veuillez les corriger.";
            throw new Exception($message);
        }
        //Tout est valide on peut passer à la génération du clip
        $clip_path = clip_source($clip, $file_source);
    }

    $report = report_clip_generation();
    log_clip_generation($report);
}

/**
 * Retourne un rapport sur la génération d'extraits
 * @see generate_clips()
 * @return array Un tableau de rapport
 */
function report_clip_generation(): array
{
    //Produit un rapport sur génération de clips : liste des extraits générés, erreurs éventuelles. A peaufiner
    return array(
        'Clips générés avec succès !'
    );
}

/**
 * Log un rapport sur la génération des extraits
 * @param array Un tableau contenants des entrées d'un rapport
 * @see report_clip_generation()
 */
function log_clip_generation(array $report)
{
    foreach ($report as $entry) {
        error_log($entry);
    }
}


/**
 * Produit un extrait de la vidéo source et retourne le path
 * @param DOMElement $clip L'élément extrait qui définit l'extrait à préparer
 * @param string $file_source Le fichier source à cliper
 * @throws Exception FFMPEG
 */
function clip_source(DOMElement $clip, string $file_source)
{
    $ffmpeg = FFMpeg\FFMpeg::create();


    $clip = $video->clip(FFMpeg\Coordinate\TimeCode::fromSeconds(30), FFMpeg\Coordinate\TimeCode::fromSeconds(15));
    $clip->save(new FFMpeg\Format\Video\X264(), 'video.avi');
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
