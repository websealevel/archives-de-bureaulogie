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
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/response.php';

/**
 * Traite la requête AJAX/formulaire de génération d'un extrait
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_list_clips()
{
    load_env();

    //Authentifier l'utilisateur
    if (!current_user_can('list_all_clips')) {
        header('Content-Type: application/json; charset=utf-8');
        $response =  json_encode(array(
            'statut' => 403,
            'errors' => array(
                array(
                    'name' => '',
                    'value' => '',
                    'message' => 'Vous ne disposez pas des droits nécessaires pour lister les extraits'
                )
            )
        ));
        echo $response;
        exit;
    }

    $source_url = filter_input(INPUT_POST, 'source');

    //Validation du formulaire
    if (empty($source_url)) {
        api_respond_with_error(array(
            new InputValidation('', '', 'Veuillez préciser une source')
        ));
    }

    $path_parts = pathinfo($source_url);
    $source_file = $path_parts['basename'];

    if (empty($source_file)) {
        api_respond_with_error(array(
            new InputValidation('', '', 'Veuillez préciser une source')
        ));
    }

    $html = map_declared_clips_to_html_item($source_file);
    api_respond_with_success(data: $html, key: 'extrait');
}
