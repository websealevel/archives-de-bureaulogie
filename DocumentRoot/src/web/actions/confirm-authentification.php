<?php

/**
 * Traite le formulaire de confirmation de mot de passe
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../database/queries-accounts.php';

/**
 * Vérifie le mot de passe de l'utilisateur authentifié en session
 * @global array $_POST
 * @global array $_SESSION
 */

function confirm_authentification()
{

    $password = filter_input(INPUT_POST, 'password');

    $account = sql_find_account_by_id($_SESSION['account_id']);

    $credentials = new Credentials(
        $account->pseudo,
        $account->password
    );

    //Si trouvé, on check mdp, si pas ok, on rejette
    if (!password_verify($credentials->password, $account->password)) {
        error_log_login_failed($credentials);
        redirect('/confirm-authentification', 'notices', array(
            new Notice("Nous n'avons pas réussi à vous authentifier, veuillez réssayez s'il vous plaît.", NoticeStatus::Error)
        ));
    }

    //C'est ok, on redirige vers la précédente pas avec une confirmation 'identity_checked' en session
}
