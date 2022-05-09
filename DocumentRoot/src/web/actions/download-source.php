<?php

/**
 * Traite le formulaire d'ajout de source
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/FormInput.php';
require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../../models/Credentials.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-accounts.php';

/**
 * Authentifie l'utilisateur
 * @global array $_POST
 * @global array $_SESSION
 */
function download_source()
{
    session_start();

    dd('ok');

    $form_inputs = array(
        new FormInput('source_url', $_POST['source_url'], function (string $source_url): InputValidation {
            if (empty($source_url))
                return new InputValidation('source_url', $source_url, "Renseigner une url valide de source à télécharger.");

            return new InputValidation('source_url', $source_url, '', InputStatus::Valid);
        })
    );

    $input_validations = validate_posted_form($form_inputs);

    //Filtrer que les champs avec un champs 'errors' non vide et status invalid.
    $invalid_inputs = array_filter($input_validations, function (InputValidation $input) {
        return InputStatus::Invalid === $input->status;
    });
    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($invalid_inputs))
        redirect('/download-source', 'form_errors', $input_validations);


    dd($input_validations);
    dd('Vidéo à ajouter');
}
