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
require_once __DIR__ . '/../actions/download-source.php';

function api_download_source()
{    
    session_id($_POST['PHPSESSID']);
    session_start();

    if (!current_user_can('add_source')) {
        echo 'Autorisation refusée';
        exit;
    }

    //Lancement du téléchargement de la source

    web_download_source();


    echo json_encode(array(
        'message' => 'Bonjour ' . $_SESSION['pseudo']
    ));
}
