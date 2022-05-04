<?php

/**
 * Traite le formulaire de login
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
function log_in()
{
    start_session();

    $form_inputs = array(

        new FormInput('login', $_POST['login'], function (string $login): InputValidation {
            if (empty($login))
                return new InputValidation('login', $login, 'Veuillez fournir votre pseudo pour vous identifiez.');

            return new InputValidation('login', $login, '', InputStatus::Valid);
        }),

        new FormInput('password', $_POST['password'], function (string $password): InputValidation {
            if (!$password || mb_strlen($password) < 6)
                return new InputValidation('password', $password, 'Le mot de passe doit faire au moins 6 caractères');

            return new InputValidation('password', $password, '', InputStatus::Valid);
        }),

    );

    $input_validations = validate_posted_form($form_inputs);

    //Filtrer que les champs avec un champs 'errors' non vide et status invalid.
    $invalid_inputs = array_filter($input_validations, function (InputValidation $input) {
        return InputStatus::Invalid === $input->status;
    });
    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($invalid_inputs)) {
        $_SESSION['form_errors'] = $input_validations;
        redirect('/');
    }

    //On tente de log
    $credentials = new Credentials(
        $input_validations['login']->value,
        my_hash_password($input_validations['password']->value)
    );

    $result = log_user($credentials);

    var_dump($result);
    die;
    dd($result);

    //On regenere le sessions id.
    session_regenerate_id(true);
}
