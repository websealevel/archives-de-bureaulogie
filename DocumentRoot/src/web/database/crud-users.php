<?php

/**
 * Fonctions CRUD users
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../router/router.php';
require_once __DIR__ . '/queries_user.php';

/**
 * Insère un utilisateur en base de données
 * @param User $user
 * @return string|bool L'id de l'utilisateur inséré
 */
function create_account(User $user): string|bool
{
    try {
        $id = sql_insert_account($user->pseudo, $user->password, $user->email);
    } catch (PDOException $e) {
        error_log($e);
        $_SESSION['notices'] = array(
            new Notice("Un membre de l'Université Libre de Bureaulogie porte déjà ce pseudonyme ou dispose déjà de cet email. Veuillez en essayer un autre s'il vous plaît", NoticeStatus::Error)
        );
        redirect('/sign-up');
    }

    return $id;
}

/**
 * Authentifie un compte utilisateur à partir de son pseudo/email et mot de passe
 * Retourne l'id de l'utilisateur s'il existe, 
 * @param array $credentials Les credentials POSTé par l'user (pseudo/email, mot de passe)
 */
function log_user(array $credentials)
{

    $pseudo = $credentials['login'];;

    try {
        $account = sql_find_account_by_pseudo($pseudo);
    } catch (PDOException $e) {
        error_log($e);
        $_SESSION['notices'] = array(
            new Notice("Impossible de trouver votre compte, veuillez réessayer s'il vous plaît.", NoticeStatus::Error)
        );
        redirect('/');
    }

    //Si trouvé, on check mdp, si pas ok, on rejette

    //Sinon on log


}
