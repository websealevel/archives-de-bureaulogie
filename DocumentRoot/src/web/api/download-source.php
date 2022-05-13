<?php

/**
 * Gere requete AJAX pour télécharger une vidéo source
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../current-user.php';

function api_download_source()
{
    session_id($_POST['PHPSESSID']);
    session_start();
    write_log($_POST);


    if (!current_user_can('add_source')) {
        echo 'Autorisation refusée';
        exit;
    }

    echo json_encode(array(
        'message' => 'Bonjour ' . $_SESSION['pseudo']
    ));
}
