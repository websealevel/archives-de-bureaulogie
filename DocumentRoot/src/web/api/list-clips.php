<?php

/**
 * Gere requete AJAX pour récupérer la liste des extraits déclarés pour une source
 *
 * @link
 *
 * @package wsl 
 */


/**
 * Functions
 */
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';


/**
 * Traite la requête AJAX/formulaire de génération d'un extrait
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_list_clips()
{

    $source_url = filter_input(INPUT_POST, 'source');

    if (empty($source_url)) {
        api_respond_with_error(array(
            new InputValidation('', '', 'Veuillez préciser une source')
        ));
    }

    $path_parts = pathinfo($source_url);
    $source_file = $path_parts['basename'];

    $html = map_declared_clips_to_html_item($source_file);

    write_log($html);

    header('Content-Type: application/json; charset=utf-8');
    $response = json_encode(array(
        'statut' => 200,
        'extrait' => $html
    ));
    echo $response;
    exit;
}
