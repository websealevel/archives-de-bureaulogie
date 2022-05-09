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
 * Ecrit sur la sortie standard les sources sous la forme d'un select
 * @param string $name_attr L'attribut name du select
 * @return void
 */
function esc_sources_to_html_select_e(string $name_attr = 'sources'): void
{
    $options = map_declared_sources_to_select_options();

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
 * Retourne une liste de sources sous forme d'options HTML
 * @param string $filter Un filtre sur les sources à appliquer. Optional. Default = 'all'
 * @return array 
 */
function map_declared_sources_to_select_options(string $filter = "all"): array
{
    $sources = query_declared_sources();

    $options = array_map(function ($source) {
        return map_source_to_option($source);
    }, iterator_to_array($sources));

    return $options;
}

/**
 * Retourne une source au format option HTML
 * @param DOMElement $source Un élément source
 * @return string L'élément source au format d'option HTML
 */
function map_source_to_option(DOMElement $source): string
{
    $name = $source->getAttribute('name');
    $label = $source->getAttribute('label');
    return sprintf('<option name="%s">%s</option>', $name, $label);
}

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
    dd($url);
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
