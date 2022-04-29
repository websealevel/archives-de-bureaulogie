<?php

/**
 * Traite le formulaire de login
 * @link
 *
 * @package wsl 
 */

/**
 * Authentifie l'utilisateur
 * @global array $_POST
 */
function authentificate_user()
{
    printf("Voyons voir qui est là\n");
    dump($_POST);
    return;
}
