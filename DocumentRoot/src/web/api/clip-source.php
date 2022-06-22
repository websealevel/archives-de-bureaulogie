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

    //Check le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'submit_clip'))) {
        api_respond_with_error();
    }


    //Validation des inputs du formulaire
    $input_validations = check_download_source_form();
    $invalid_inputs = filter_invalid_inputs($input_validations);

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

/**
 * Retourne les inputs validés (ou non) du formulaire de soumission d'extrait
 * @return InputValidation[] 
 * @global $_POST
 */
function check_submit_clip_form()
{
    $form_inputs = array(

        new FormInput('source_url', filter_input(INPUT_POST, 'source_url'), function (string $source_url): InputValidation {

            //Non vide.
            if (!isset($source_url) || empty($source_url))
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");


            //Contrainte sur la chaine youtube (juste celle de canardPC)
            return new InputValidation('source_url', $source_url, '', InputStatus::Valid);
        }),

    );

    return validate_posted_form($form_inputs);
}
