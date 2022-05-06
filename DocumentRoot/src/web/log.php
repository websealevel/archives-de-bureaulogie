<?php

/**
 * Un wrap de error_log amélioré
 *
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/../models/Credentials.php';
require_once __DIR__ . '/../models/Notice.php';

if (!function_exists('write_log')) {

    /**Ecrit un log
     *
     * @param mixed $log
     */
    function write_log($log)
    {

        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}

/**
 * Log une tentative échouée de login
 * @param Credentials $credentials Les credentials POSTé par l'utilisateur
 * @global array $_SERVER
 * @return void
 */
function error_log_login_failed(Credentials $credentials): void
{
    $message = sprintf("Tentative d'authentification échouée: login: %s - IP: %s - date: %s", $credentials->login, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}

/**
 * Log une authentification réussie
 * @param stdClass $account Les infos du compte authentifié
 * @global array $_SERVER
 * @return void
 */
function error_log_login_success($account): void
{
    $message = sprintf("Authentification réussie: login: %s - IP: %s - date: %s", $account->pseudo, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}

/**
 * Log un logout réussi
 * @param string $login
 * @global array $_SERVER
 * @return void
 */
function error_log_out_success(string $login, Notice $notice): void
{
    $message = sprintf("Logout: status: %s login: %s - IP: %s - date: %s", $notice->status, $login, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}
