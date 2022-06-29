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
 * Retourne un extrait au format option ou li HTML
 * @param DOMElement $clip Un élément extrait d'une source
 * @param string $html_item Un élément html de liste (option ou li)
 * @param array $show_data Les données à afficher. Optional. [Pas encore implémentée]
 * @return string L'élément extrait au format d'option HTML
 */
function map_clip_to_html_item(DOMElement $clip, string $html_item, array $show_data = array('title, description, debut, fin, auteur, cree_le'),): string
{

    $title = child_element_by_name($clip, 'title')->nodeValue;
    $description = child_element_by_name($clip, 'description')->nodeValue;
    $timecode_start = child_element_by_name($clip, 'debut')->nodeValue;
    $timecode_end = child_element_by_name($clip, 'fin')->nodeValue;
    $author = child_element_by_name($clip, 'auteur')->nodeValue;
    $created_on = child_element_by_name($clip, 'cree_le')->nodeValue;

    $source = declared_source_of($clip);
    $source_parts = pathinfo(source_name($source));

    $clip_name = build_clip_name($source_parts['filename'], $timecode_start, $timecode_end);

    $src = path_clip($clip_name);

    $html = html_clip_item($title, $description, $timecode_start, $timecode_end, $author, $created_on, $src);

    return sprintf(
        '<%s name="%s">%s</%s>',
        $html_item,
        $src,
        $html,
        $html_item
    );
}
/**
 * Retourne le markup d'un extrait en élément de liste
 * @param string $title Le titre de l'extrait
 * @param string $description La description de l'extrait
 * @param string $src Le chemin de l'extrait
 */
function html_clip_item(string $title, string $description, string $timecode_start, string $timecode_end, string $author, string $created_on, string $src): string
{
    $summary = sprintf('<div class="clip-item-header">%s %s-%s %s</div>', $title, $timecode_start, $timecode_end, html_download_link($src));

    $details = sprintf("%s %s <small>%s</small> <small>auteur: %s, crée le: %s</small>", html_video_markup($src, 500), html_download_link($src), $description, $author, $created_on);

    return html_details(
        $summary,
        $details
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
    <source src="%s" type="video/mp4">
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
    return sprintf('
    <details>
    <summary>%s</summary>
    %s
    </details>
    ', $summary, $detail);
}

/**
 * Retourne une liste de sources sous forme d'options HTML
 * @param string $html_item Le type d'item (li ou option). Optional. Default = li
 * @param array $show_data les données à afficher dans le markup. Optional. Default = array('label')
 * @param string $filter Un filtre sur les sources à appliquer. Optional. Default = 'all'
 * @return array 
 */
function map_declared_sources_to_html_item(string $html_item = 'li', array $show_data = array('label'), string $filter = "all"): array
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

/**
 * Retourne une liste de clips ordonée sous forme d'options HTML
 * @param string $source_file Le nom du fichier de la source
 * @param string $html_item Le type d'item (li ou option). Optional. Default = li
 * @param array $show_data les données à afficher dans le markup. Optional. Default = array('label')
 * @param string $filter Un filtre sur les sources à appliquer. Optional. Default = 'all'
 * @param string $order_by L'ordre dans lequel sont présentés les extraits. Optional. Default = 'timecode_start'
 * @return array 
 */
function map_declared_clips_to_html_item(string $source_file, string $html_item = 'li', string $filter = "all", string $order_by = 'timecode_start'): array
{
    if (!in_array($html_item, array('li', 'option'))) {
        throw new Exception("html_item invalide.");
    }

    $clips = query_declared_clips_of($source_file);

    //Les trier par ordre de timecodestart

    $options = array_map(function ($clip) use ($html_item) {
        return map_clip_to_html_item($clip, $html_item);
    }, iterator_to_array($clips));

    return $options;
}
