<?php

/**
 * Traite le formulaire de loing 
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
