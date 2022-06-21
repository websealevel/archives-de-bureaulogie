<?php

/**
 * Contient toutes les fonctions markup HTML 
 * @link
 *
 * @package wsl 
 */

/**
 * Retourne une source au format option ou li HTML
 * @param DOMElement $source Un élément source
 * @param string $html_item Un élément html de liste (option ou li)
 * @return string L'élément source au format d'option HTML
 */
function map_source_to_html_item(DOMElement $source, string $html_item): string
{
    $name = $source->getAttribute('name');
    $src = path_source($name);
    $label = $source->getAttribute('label');

    return sprintf(
        '
    <%s name="%s">%s</%s>',
        $html_item,
        $src,
        html_details($label, html_video_markup($src, 500)),
        $html_item
    );
}

/**
 * Retourne une balise vidéo HTML5
 * @param string $src Le path de la vidéo
 * @param int $width La largeur de la vidéo. Optional
 * @return string
 */
function html_video_markup(string $src, int $width = 400): string
{
    return sprintf('
    <video controls width="%d">
    <source src="%s" type="video/webm">
    Désolé, votre navigateur ne supporte pas le tag video HTML5</video>
    ', $width, $src);
}

/**
 * Retourne une balise details HTML5
 * @param string 
 */
function html_details(string $summary, string $detail)
{
    return sprintf('
    <details>
    <summary>%s</summary>
    %s
    </details>
    ', $summary, $detail);
}

/**
 * Retourne une liste de sources sous forme d'options HTML
 * @param string $filter Un filtre sur les sources à appliquer. Optional. Default = 'all'
 * @return array 
 */
function map_declared_sources_to_html_item(string $html_item, string $filter = "all"): array
{
    if (!in_array($html_item, array('li', 'option'))) {
        throw new Exception("html_item invalide.");
    }

    $sources = query_declared_sources();

    $options = array_map(function ($source) use ($html_item) {
        write_log($source);
        return map_source_to_html_item($source, $html_item);
    }, iterator_to_array($sources));

    return $options;
}
