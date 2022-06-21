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
 * @param array $show_data Les données à afficher. Optional. Default = 'label
 * @return string L'élément source au format d'option HTML
 */
function map_source_to_html_item(DOMElement $source, string $html_item, array $show_data = array('label')): string
{
    $name = $source->getAttribute('name');
    $src = path_source($name);
    $label = $source->getAttribute('label');

    if (1 === count($show_data) && in_array('label', $show_data))
        $html = $label;
    else if (in_array('details', $show_data))
        $html = html_details($label, html_video_markup($src, 500) . html_download_link($src));
    else
        $html = $label;

    return sprintf(
        '<%s name="%s">%s</%s>',
        $html_item,
        $src,
        $html,
        $html_item
    );
}

/**
 * Retourne un lien de téléchargement d'une ressource
 * @param string $ressource_abs_path Le chemin absolu de la ressource
 * @return string
 */
function html_download_link($ressource_abs_path, $label = 'Télécharger'): string
{
    return sprintf('<a download href="%s">%s</a>', $ressource_abs_path, $label);
}

/**
 * Retourne une balise vidéo HTML5
 * @param string $src Le path de la vidéo
 * @param int $width La largeur de la vidéo. Optional
 * @return string
 * @link https://developer.mozilla.org/fr/docs/Web/HTML/Element/video
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
 * @param string $summary Le résumé
 * @param string $detail Les détails
 * @return string
 * @link https://developer.mozilla.org/fr/docs/Web/HTML/Element/details
 */
function html_details(string $summary, string $detail): string
{

    write_log($detail);

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
function map_declared_sources_to_html_item(string $html_item, array $show_data = array('label'), string $filter = "all"): array
{
    if (!in_array($html_item, array('li', 'option'))) {
        throw new Exception("html_item invalide.");
    }

    $sources = query_declared_sources();

    $options = array_map(function ($source) use ($html_item, $show_data) {
        return map_source_to_html_item($source, $html_item, $show_data);
    }, iterator_to_array($sources));

    return $options;
}
