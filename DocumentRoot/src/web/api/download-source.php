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

function api_download_source()
{
    session_id($_COOKIE['PHPSESSID']);
    session_start();
    write_log($_POST);
    write_log($_SESSION);
    echo json_encode(array(
        'message' => 'success'
    ));
}
