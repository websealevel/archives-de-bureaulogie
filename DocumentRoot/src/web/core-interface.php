<?php

/**
 * Toutes les fonctions de l'interface entre l'appli web et la partie core de l'appli
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/database/repository-downloads.php';
require_once __DIR__ . '/database/repository-accounts.php';
require_once __DIR__ . '/../core/query.php';
require_once __DIR__ . '/../core/actions.php';

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
        write_log($source);
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

    echo sprintf('<select name="%s" id="%s">', $name_attr, $name_attr);
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

/**
 * Ecrit sur la sortie standard l'historique des téléchargements de vidéos sources sous la forme d'items li
 * @return void
 */
function esc_download_history_items_e(): void
{
    $history = download_history();
    foreach ($history as $download) {

        $account = find_account_by_id($download['account_id']);

        $created_on = date('d-m-Y à H:i:s', strtotime($download['created_on']));

        $item = sprintf(
            '
        <li class="download-state %s">utilisateur: %s - url: %s - démarré le: %s - temps total de téléchargement (min): %s - nom du fichier: %s - statut: %s
        </li>',
            $download['state'],
            $account->pseudo,
            $download['url'],
            $created_on,
            $download['totaltime'],
            $download['filename'],
            $download['state']
        );

        echo ($item);
    }
    return;
}
/**
 * Ecrit sur la sortie standard le nombre de téléchargements en cours
 * @return void
 */
function esc_active_downloads_info_e()
{
    $active_downloads = active_downloads();
    if (0 === count($active_downloads))
        return;
    esc_html_e(sprintf("%d téléchargement(s) en cours", count($active_downloads)));
    return;
}
