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
        api_respond_with_error();
    }

    write_log($_POST);
    write_log($_SESSION);

    //Check le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'submit_clip'))) {
        api_respond_with_error();
    }

    //Utilisateur authentifié et token valide. Création du clip
    sleep(5);
}

/**
 * Retourne une erreur de l'api au client, avec un message et un status code
 * @param string $message Le message a renvoyé au client
 * @param string $code Le code HTTP de la requête
 * @return void
 */
function api_respond_with_error(string $message = 'Vous ne disposez pas des droits nécessaires', string $code = '403'): void
{
    header('Content-Type: application/json; charset=utf-8');
    $response =  json_encode(array(
        'statut' => $code,
        'errors' => array(
            array(
                'name' => '',
                'value' => '',
                'message' => $message
            )
        )
    ));
    echo $response;
    exit;
}
