<?php

/**
 * Roles et capabilities
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../models/enumRolesId.php';


/**
 * Retourne vrai si l'utilisateur courant a la capacité de faire cette action, faux sinon
 * @param string $cap La capacité à tester
 * @return bool
 * @throws Exception - Si la capacité n'existe pas
 */
function current_user_can(string $cap): bool
{

    //Check que la cap existe

    //Récupere le compte et son role

    //Check si une 2eme authentification est demandée (sécuritée renforcée sur certaine actions). Si c'est le cas redirect vers un petit écran confirmation du mot de passe.

    //Check si le role a la cap

    return false;
}
