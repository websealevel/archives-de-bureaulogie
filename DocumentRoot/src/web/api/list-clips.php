<?php

/**
 * Gere requete AJAX pour récupérer la liste des extraits déclarés pour une source
 *
 * @link
 *
 * @package wsl 
 */


/**
 * Functions
 */
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';


/**
 * Traite la requête AJAX/formulaire de génération d'un extrait
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_list_clips()
{
    write_log('api_list_clips');
    echo 'list clips';
    exit;
}
