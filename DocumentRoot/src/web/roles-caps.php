<?php

/**
 * Roles et capabilities
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../models/enumRolesId.php';

/**
 * Retourne vrai si une capacité existe, lève une exception sinon (erreur 500)
 * @param string $cap La capability
 * @return bool 
 * @throws Exception - Si la capacité n'existe pas en base.
 */
function is_cap_defined(string $cap): bool
{
    return false;
}
