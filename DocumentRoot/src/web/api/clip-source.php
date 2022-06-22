<?php

/**
 * Gere requete AJAX pour creer un clip
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Vendor
 */
require_once __DIR__ . '/../../../vendor/autoload.php';


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
function api_clip_source()
{
    load_env();

    //Authentifier l'utilisateur
    if (!current_user_can('submit_clip')) {
        header('Content-Type: application/json; charset=utf-8');
        $response =  json_encode(array(
            'statut' => 403,
            'errors' => array(
                array(
                    'name' => '',
                    'value' => '',
                    'message' => 'Vous ne disposez pas des droits nécessaires pour soumettre un extrait'
                )
            )
        ));
        echo $response;
        exit;
    }

    write_log($_POST);


    //Check le token

    //Check le nombre de jetons disponibles pour soumettre un clip


}
