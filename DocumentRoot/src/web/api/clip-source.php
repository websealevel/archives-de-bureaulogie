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

    sleep(1);

    //Authentifier l'utilisateur
    if (!current_user_can('submit_clip')) {
        api_respond_with_error();
    }
    //Check le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'submit_clip'))) {
        api_respond_with_error();
    }


    //Validation des inputs du formulaire
    $input_validations = check_submit_clip_form();
    $invalid_inputs = filter_invalid_inputs($input_validations);

    write_log($invalid_inputs);

    if (!empty($invalid_inputs)) {
        //Envoyez le tableau d'erreurs
        api_respond_with_error($invalid_inputs);
    }

    return 'ok';

    //Utilisateur authentifié,token valide, formulaire validé. Création du clip

}

/**
 * Retourne une erreur de l'api au client, avec un message et un status code
 * @param InputValidation[] $input_errors Les erreurs sur chaque champ
 * @param string $code Le code HTTP de la requête
 * @return void
 */
function api_respond_with_error(array $invalid_inputs = array(
    new InputValidation('', '', 'Les données transmises ne sont pas valides')
), string $code = '403'): void
{
    header('Content-Type: application/json; charset=utf-8');
    $response =  json_encode(array(
        'statut' => $code,
        'errors' => array_map(function ($invalid_input) {
            return array(
                'name' => $invalid_input->name,
                'value' => $invalid_input->value,
                'message' => $invalid_input->message
            );
        }, $invalid_inputs)
    ));
    echo $response;
    exit;
}

/**
 * Retourne les inputs validés (ou non) du formulaire de soumission d'extrait
 * @return InputValidation[] 
 * @return InputValidation[] Un tableau de champs validés
 * @global $_POST
 */
function check_submit_clip_form()
{
    $form_inputs = array(

        new FormInput('timecode_start', filter_input(INPUT_POST, 'timecode_start'), function (string $timecode_start): InputValidation {

            //Non vide.
            if (!isset($timecode_start) || empty($timecode_start))
                return new InputValidation('timecode_start', $timecode_start, "Renseignez un timecode de début non vide");

            //Respecte un format regex

            return new InputValidation('timecode_start', $timecode_start, '', InputStatus::Valid);
        }),

    );

    return validate_posted_form($form_inputs);
}
