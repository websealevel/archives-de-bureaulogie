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
function esc_sources_to_html_select_e(string $name_attr = 'sources')
{
    $options = map_declared_sources_to_select_options();

    $label = 'Veuillez choisir la vidéo dont vous souhaitez faire un extrait';

    echo sprintf('<label for="%s">%s</label>', $name_attr, $label);

    echo sprintf('<select name="%s">', $name_attr);
    foreach ($options as $option) {
        echo $option;
    }
    echo sprintf('</select>');
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
