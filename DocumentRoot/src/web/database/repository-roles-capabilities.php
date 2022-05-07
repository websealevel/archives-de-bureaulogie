<?php

/**
 * 
 * Fonctions Read Roles/Capabilities
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/queries-roles-capabilities.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../log.php';


/**
 * Retourne vrai si la capacité existe, faux sinon
 * @param string $cap Le nom de la capacité à tester
 * @return bool
 */
function cap_exists(string $cap): bool
{
    $result = sql_find_capacity_by_name($cap);
    return boolval($result);
}

/**
 * Retourne vrai si le rôle donne droit à la capacité
 * @param string $role Le rôle
 * @param string $cap La capacité
 * @throws Exception - Si le role n'existe pas ou si la capacité n'existe pas
 * @return bool
 */
function role_has_cap(string $role, string $cap): bool
{

    //Intersting stuff here!


    return false;
}

/**
 * Renvoie vrai si une deuxième authentification de sécurité est requise pour cette capacité, faux sinon
 * @param string $cap La capacité
 * @return bool
 */
function is_authentification_confirmation_required(string $cap): bool
{
    return true;
}
