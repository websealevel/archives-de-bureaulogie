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
