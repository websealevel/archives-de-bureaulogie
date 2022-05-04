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


/**
 * Déconnecte un utilisateur (en session)
 * @return void
 * @global $_SESSION
 */
function log_out()
{
    session_start();
    logout_user_session();
    redirect('/', 'notices', array(
        new Notice("Vous avez été déconnecté avec succès", NoticeStatus::Success)
    ));
}

/**
 * Supprime le compte utilisateur en session
 * @global array $_SESSION
 * @return void
 */
function logout_user_session()
{
    if (!isset($_SESSION))
        throw new Exception("Aucune session n'est ouverte");

    session_regenerate_id();
    session_destroy();
}
