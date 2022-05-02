<?php

/**
 * Traite le formulaire de création de compte
 * Un compte est identifié par son email (unique)
 * Un pseudo est alphanumérique (on rajoute un code aléatoire comme sur discord)
 *
 * @package wsl 
 */

autoload();
require __DIR__ . '/../../models/FormInput.php';
require __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../utils.php';

/**
 * Crée un compte utilisateur si l'utilisateur n'existe pas déjà
 * @global array $_SESSION
 * @global array $_POST
 * @return void
 */
function sign_up_user()
{

    error_log('yo');

    $form_inputs = array(
        new FormInput('pseudo', $_POST['pseudo'], function (string $pseudo): InputValidation {
            if (empty($pseudo))
                return new Input('pseudo', $pseudo, 'Le pseudo ne peut pas être vide');

            if (!preg_match('/[^a-z0-9]/i', $pseudo)) {
                return new InputValidation('pseudo', 'Le pseudo ne peut contenir que des caractères alphanumériques.');
            }

            return new InputValidation('pseudo', '', InputStatus::Valid);
        }),
        new FormInput('email', $_POST['email'], function (string $email): InputValidation {
            return new InputValidation('email', '');
        }),

        new FormInput('password', $_POST['password'], function (string $password): InputValidation {
            return new InputValidation('password', '');
        }),

        new FormInput('password_confirmation', $_POST['password_confirmation'], function (string $password_confirmation): InputValidation {
            return new InputValidation('password_confirmation', '');
        }),
    );

    die;

    $input_validations = validate_posted_form($form_inputs);

    //Filtrer que les champs avec un champs 'errors' non vide et status invalid.

    $invalid_inputs = array_filter($input_validations, function (Input $input) {
        return InputStatus::Invalid === $input->status;
    });

    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($invalid_inputs)) {
        $_SESSION['form_errors'] = $invalid_inputs;
        redirect('/sign-up');
    }

    //C'est ok, on crée le compte

    //On renvoie l'utilisateur vers la home avec un message disant connectez vous (pre fill login et password)
    redirect('/');
}
