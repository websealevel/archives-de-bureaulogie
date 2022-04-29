<?php

/**
 * Traite le formulaire de création de compte
 * Un compte est identifié par son email (unique)
 * Un pseudo est alphanumérique (on rajoute un code aléatoire comme sur discord)
 *
 * @package wsl 
 */

autoload();
require __DIR__ . '/../../models/Input.php';

function sign_up_user()
{
    $errors = array();

    $input_names = array(
        'pseudo',
        'email',
        'password',
        'password_confirmation'
    );


    foreach ($input_names as $input_name) {

        if (!isset($_POST["{$input_name}"]) || empty($_POST["{$input_name}"])) {
            $errors["{$input_name}"] = new Input(
                $input_name,
                $_POST["{$input_name}"],
                "Le champ ne peut pas être vide, veuillez le remplir."
            );
        } else {
            $errors["{$input_name}"] = new Input(
                $input_name,
                $_POST["{$input_name}"],
                ""
            );
        }
    }

    if (!empty($errors)) {
        //On redirige vers la page de création de compte
        session_start();
        $_SESSION['form_errors'] = $errors;
        header('Location: sign-up');
    }
}

function is_valid_pseudo(): bool
{
}
