<?php

/**
 * Un wrap de error_log amélioré
 *
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/../models/Credentials.php';
require_once __DIR__ . '/../models/enumDownloadState.php';
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
    $message = sprintf("Tentative d'authentification échouée: LOGIN: %s - IP: %s - DATE: %s", $credentials->login, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
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
    $message = sprintf("Authentification réussie: LOGIN: %s - IP: %s - DATE: %s", $account->pseudo, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}

/**
 * Log un logout réussi
 * @param string $login
 * @param Notice $notice
 * @global array $_SERVER
 * @return void
 */
function error_log_out_success(string $login, Notice $notice): void
{
    $message = sprintf("Logout: STATUS: %s - LOGIN: %s - IP: %s - DATE: %s", $notice->status->value, $login, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}


/**
 * Log un téléchargement
 * @param string $account_id L'id du compte utilisateur ayant initié le téléchargement
 * @param string $url L'url de la vidéo à télcharger
 * @param string $filename Le nom du fichier sous lequel est enregistré le téléchargement
 * @param DownloadState $download_state L'état du téléchargement
 * @return void
 */
function error_log_download(string $account_id, string $url, string $filename, DownloadState $download_state): void
{
    $message = sprintf("Download STATE: %s - URL: %s - FILENAME: %s - ACCOUNT_ID: %s - IP: %s - DATE: %s", $download_state->value, $url, $filename, $account_id, $_SERVER['REMOTE_ADDR'], date('Y-m-d H:i:s'));
    error_log($message);
    return;
}
