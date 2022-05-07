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
