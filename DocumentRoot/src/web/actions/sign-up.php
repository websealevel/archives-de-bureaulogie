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
    //Pourquoi je dois redémarrer la session ici ? Début de chaque script ?
    start_session();

    $form_inputs = array(
        new FormInput('pseudo', $_POST['pseudo'], function (string $pseudo): InputValidation {
            if (empty($pseudo))
                return new InputValidation('pseudo', $pseudo, 'Le pseudo ne peut pas être vide');

            if (preg_match('/[^a-z_\-0-9]/i', $pseudo)) {
                return new InputValidation('pseudo', $pseudo, 'Le pseudo ne peut contenir que des caractères alphanumériques.');
            }

            return new InputValidation('pseudo', $pseudo, 'OK', InputStatus::Valid);
        }),
        new FormInput('email', $_POST['email'], function (string $email): InputValidation {
            return new InputValidation('email', $email, '');
        }),

        new FormInput('password', $_POST['password'], function (string $password): InputValidation {
            return new InputValidation('password', $password, '');
        }),

        new FormInput('password_confirmation', $_POST['password_confirmation'], function (string $password_confirmation): InputValidation {
            return new InputValidation('password_confirmation', $password_confirmation, '');
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
        redirect('/sign-up');
    }

    //C'est ok, on crée le compte

    //On renvoie l'utilisateur vers la home avec un message disant connectez vous (pre fill login et password)
    redirect('/');
}
