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

    $input_names = array(
        'pseudo' => array(
            //Alphanumérique et non vide
            'validation_callback' => function (string $pseudo): Input {
                if (empty($pseudo))
                    return new Input('pseudo', $pseudo, 'Le pseudo ne peut pas être vide');

                if (!preg_match('/[^a-z0-9]/i', $pseudo)) {
                    return new Input('pseudo', $pseudo, 'Le pseudo ne peut contenir que des caractères alphanumériques.');
                }

                return new Input('pseudo', $pseudo, '', InputStatus::Valid);
            },
            'errors' => array()
        ),
        'email' => array(
            'validation_callback' => function (string $email): Input {
                return new Input('email', $email, '', InputStatus::Valid);
            },
            'errors' => array(),
        ),
        'password' => array(
            'validation_callback' => function (string $password): Input {
                return new Input('password', $password, '', InputStatus::Valid);
            },
            'errors' => array(),
        ),
        'password_confirmation' => array(
            'validation_callback' => function (string $password_confirmation): Input {
                return new Input('password_confirmation', $password_confirmation, '', InputStatus::Valid);
            },
            'errors' => array(),
        ),
    );

    foreach ($input_names as $name => $input) {

        //Check que les champs sont présents
        if (!isset($_POST["{$name}"])) {

            $input_names["{$name}"]['errors'][] = new Input(
                $name,
                $_POST["{$name}"],
                "Le champ ne peut pas être vide, veuillez le remplir.",
                InputStatus::Invalid
            );
        }

        //Appelle la callback de validation sur chaque champ
        $validation_callback = $input['validation_callback'];

        if (!is_callable($validation_callback)) {
            throw new Exception("La callback de validation n'est pas callable.");
        }
        $input['errors'][] = $validation_callback($_POST["{$name}"]);
    }


    //Filtrer que les champs avec un champs 'errors' non vide et status invalid.

    $invalid_inputs = array_filter($input_names, function ($input) {
        return !empty($input['errors']);
    });

    $errors = array_map(function ($input) {
        return $input['errors'];
    }, $input_names);

    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        header('Location: sign-up');
    }

    //C'est ok, on crée le compte


    //On renvoie l'utilisateur vers la home avec un message disant connectez vous (pre fill login et password)

    header('Location: /');
}
