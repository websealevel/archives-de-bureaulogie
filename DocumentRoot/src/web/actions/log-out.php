<?php

/**
 * Traite le formulaire de logout
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../actions/log-out.php';
require_once __DIR__ . '/../log.php';


/**
 * Déconnecte un utilisateur (en session)
 * @return void
 * @global $_SESSION
 */
function log_out(Notice $notice = new Notice("Vous avez été déconnecté avec succès", NoticeStatus::Success))
{
    session_start();
    if (isset($_SESSION)) {
        $login = logout_user_session();
        error_log_out_success($login, $notice);
        redirect('/', 'notices', array(
            $notice
        ));
    } else {
        redirect('/', 'notices', array(
            $notice
        ));
    }
}

/**
 * Supprime le compte utilisateur en session
 * @global array $_SESSION
 * @return string Le login de l'utilisateur
 */
function logout_user_session(): string
{
    if (!isset($_SESSION))
        throw new Exception("On ne devrait pas chercher à logout un user qui n'est pas connecté.");

    $login = $_SESSION['pseudo'] ?? '';
    session_regenerate_id();
    session_destroy();
    return $login;
}
