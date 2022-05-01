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
            'errors' => array(),
            'validation_callback' => function (string $email): Input {
                return new Input('email', $email, '', InputStatus::Valid);
            }
        ),
        'password' => array(
            'errors' => array(),
            'validation_callback' => function (string $password): Input {
                return new Input('password', $password, '', InputStatus::Valid);
            }
        ),
        'password_confirmation' => array(
            'errors' => array(),
            'validation_callback' => function (string $password_confirmation): Input {
                return new Input('password_confirmation', $password_confirmation, '', InputStatus::Valid);
            }
        ),
    );

    $input_validation = array();


    foreach ($input_names as $input_name) {

        //Check que les champs sont présents
        if (!isset($_POST["{$input_name}"])) {

            $input_names["{$input_name}"]['errors'][] = new Input(
                $input_name,
                $_POST["{$input_name}"],
                "Le champ ne peut pas être vide, veuillez le remplir.",
                InputStatus::Invalid
            );
        }

        //Appelle la callback de validation sur chaque champ
        $validation_callback = $input_names["{$input_name}"]['validation_callback'];

        if (!is_callable($validation_callback)) {
            throw new Exception("La callback de validation n'est pas callable.");
        }
        $input_names["{$input_name}"]['errors'][] = $validation_callback();
    }

    $invalid_inputs = array_filter($input_validation, function ($validation) {
        return InputStatus::Invalid === $Input->status;
    });
    
    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($invalid_inputs)) {
        $_SESSION['form_errors'] = $errors;
        header('Location: sign-up');
    }

    //C'est ok, on crée le compte
}
