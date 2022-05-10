<?php

/**
 * Toutes les fonctions de l'interface entre l'appli web et la partie core de l'appli (édition et manipulation du fichier source)
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../core/query.php';

/**
 * PATHS
 */

/**
 * Retourne le path des downloads sur le serveur
 * @return string Le path des downloads
 */
function web_downloads_path(): string
{
    $path = sprintf("%s", PATH_DOWNLOADS);
    return $path;
}

/**
 * Retourne le path des sources sur le serveur
 * @return string Le path des downloads
 */
function web_sources_path(): string
{
    $path = sprintf("%s", PATH_SOURCES);
    return $path;
}

/**
 * Retourne le path des clips sur le serveur
 * @return string Le path des clips
 */
function web_clips_path(): string
{
    $path = sprintf("%s", PATH_CLIPS);
    return $path;
}

/**
 * Retourne le path d'un clip sur le serveur
 * @return string Le path des clips
 * @throws Exception - Si le clip n'existe pas
 */
function web_clip_path(string $clip_name): string
{
    $path = web_clips_path();
    $clip_path = sprintf("%s/%s", $path, $clip_name);
    if (!file_exists($clip_path))
        throw new Exception("L'extrait n'existe pas sur le serveur.");
    return $clip_path;
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
        return map_source_to_html_item($source, $html_item);
    }, iterator_to_array($sources));

    return $options;
}

/**
 * Ecrit sur la sortie standard les sources sous la forme d'un select
 * @param string $name_attr L'attribut name du select
 * @return void
 */
function esc_sources_to_html_select_e(string $name_attr = 'sources'): void
{
    $options = map_declared_sources_to_html_item('option');

    $label = 'Veuillez choisir la vidéo dont vous souhaitez faire un extrait';

    echo sprintf('<label for="%s">%s</label>', $name_attr, $label);

    echo sprintf('<select name="%s">', $name_attr);
    foreach ($options as $option) {
        echo $option;
    }
    echo sprintf('</select>');
    return;
}

/**
 * Ecrit sur la sortie standard les sources sous la forme d'une liste
 * @param string $name_attr L'attribut name de la liste
 * @param string $label Optional. Default no. Ajouter un label à la liste
 * @return void
 */
function esc_html_list_sources_e(string $name_attr = 'sources', string $label = ''): void
{
    $options = map_declared_sources_to_html_item('li');

    if (!empty($label)) {
        $label = 'Liste des vidéos sources déjà présentes';
        echo sprintf('<label for="%s">%s</label>', $name_attr, $label);
    }

    echo sprintf('<ul name="%s">', $name_attr);
    foreach ($options as $option) {
        echo $option;
    }
    echo sprintf('</ul>');
    return;
}


/**
 * Retourne une source au format option ou li HTML
 * @param DOMElement $source Un élément source
 * @param string $html_item Un élément html de liste (option ou li)
 * @return string L'élément source au format d'option HTML
 */
function map_source_to_html_item(DOMElement $source, string $html_item): string
{
    $name = $source->getAttribute('name');
    $label = $source->getAttribute('label');
    return sprintf('<%s name="%s">%s</%s>', $html_item, $name, $label, $html_item);
}

/**
 * OUTPUT HTML
 */

/**
 * Ecrit sur la sortie standard l'url d'un clip (attribut source tag video)
 * @param string $clip_name
 * @return void
 */
function esc_video_clip_url_e(string $clip_name)
{
    $url = web_clip_path($clip_name);
    if ($url !== filter_var($url, FILTER_SANITIZE_URL)) {
        throw new Exception("L'url de l'extrait n'est pas valide.");
    }
    echo $url;
    return;
}
/**
 * Ecrit sur la sortie standard l'url d'une vidéo source (attribut source tag video)
 * @param string $source_name
 * @return void
 */
function esc_video_source_url_e(string $source_name)
{
    $url = web_clip_path($source_name);
    if ($url !== filter_var($url, FILTER_SANITIZE_URL)) {
        throw new Exception("L'url de l'extrait n'est pas valide.");
    }
    echo $url;
    return;
}

/**
 * Retourne vrai si la source est déjà déclarée (nom et url identiques ou url identiques), faux sinon
 * @param string $series La base du nom de la source
 * @param string $slug Le slug du nom de la source
 * @param string $url L'url de la source
 * @return bool
 */
function is_available_source_name(string $series, string $slug, string $url): bool
{
    $full_name = build_source_name($series, $slug);

    $match = query_source_by_name_attr($full_name);

    $match_url = $match->attributes->getNamedItem('url')->nodeValue;
    $match_name = $match->attributes->getNamedItem('name')->nodeValue;

    return $full_name === $match_name && $url === $match_url
        || $url === $match_url;
}

/**
 * Retourne le nom complet d'une source (son attribut name) à partir de sa base et de son slug/identifiant
 * @param string $series Le nom de la série à laquelle appartient la vidéo source
 * @param stirng $slug L'identifiant ajouté au nom de la vidéo
 * @return string Le nom complet au format FORMAT_FILE_VIDEO_SOURCE
 * @see FORMAT_FILE_VIDEO_SOURCE
 */
function build_source_name(string $series, string $slug): string
{
    if (empty($series) || empty($slug))
        throw new Exception("Impossible de reconstruire le nom de la source, la base du nom ou le slug est vide");

    $file_name = sprintf("%s--%s.%s", $series, $slug, EXTENSION_SOURCE);

    //Check format
    if (!preg_match('/' . FORMAT_FILE_VIDEO_SOURCE . '/', $file_name))
        throw new Exception("Une contrainte sur le nom de la source est mauvaise car le nom reconstruit de la source n'est pas dans un format valide.");

    return $file_name;
}
