<?php

/**
 * Gere requete AJAX pour gérer les marqueurs d'un utilisateur sur une vidéo source
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Vendor
 */
require_once __DIR__ . '/../../../vendor/autoload.php';


/**
 * Functions
 */
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';


/**
 * Traite la requête AJAX de gestion des marqueurs.
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_markers()
{
    echo 'coucou';
    exit;
}
