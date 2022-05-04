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
require_once __DIR__ . '/queries-accounts.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../log.php';

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

        redirect('/sign-up', 'notices', array(
            new Notice("Un membre porte déjà ce pseudonyme ou dispose déjà de cet email. Veuillez en essayer un autre s'il vous plaît", NoticeStatus::Error)
        ));
    }

    return $id;
}

/**
 * Authentifie un compte utilisateur à partir de son pseudo/email et mot de passe
 * Retourne l'id de l'utilisateur s'il existe, 
 * @param Credentials $credentials Les credentials POSTé par l'user (pseudo/email, mot de passe)
 */
function log_user(Credentials $credentials)
{

    try {
        $account = sql_find_account_by_pseudo($credentials->login);
    } catch (PDOException $e) {
        error_log($e);
        redirect('/', 'notices', array(
            new Notice("Impossible de trouver votre compte, veuillez réessayer s'il vous plaît.", NoticeStatus::Error)
        ));
    }

    if (!$account) {
        redirect('/', 'notices', array(
            new Notice("Impossible de vous identifier, veuillez réessayer s'il vous plaît.", NoticeStatus::Error)
        ));
    }

    if (!is_string($credentials->password) || !is_string($account->password)) {
        throw new Exception("Passwords ne sont pas au format attendu de chaine de caractères.");
    }


    //Si trouvé, on check mdp, si pas ok, on rejette
    if (!password_verify($credentials->password, $account->password)) {
        error_log_login_failed($credentials);
        redirect('/', 'notices', array(
            new Notice("Vos identifiants ne sont pas corrects, veuillez réessayer s'il vous plait", NoticeStatus::Error)
        ));
    }

    //Compte authentifié
    login_user_session($account);

    //Redirige vers la page d'accueil authentifié.
    redirect('/');
}
