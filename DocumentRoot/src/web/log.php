<?php

/**
 * Un wrap de error_log amélioré
 *
 * @link
 *
 * @package wsl 
 */

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
    $message = sprintf("Tentative de connexion échouée: login: %s - IP: %s - date: %s", $credentials->login, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
}
