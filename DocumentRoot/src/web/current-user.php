<?php

/**
 * Toutes les fonctions checkant et gérant l'état de l'user courant ainsi que ses droits
 *
 * @package wsl 
 */


require_once __DIR__ . '/roles-caps.php';

/**
 * Retourne vrai si l'utilisateur courant est connecté, faux sinon
 * @return bool
 */
function is_current_user_logged_in(): bool
{
    if (!isset($_SESSION))
        throw new Exception("Aucune session active");

    return boolval($_SESSION['user_authentificated'] ?? false);
}

/**
 * Retourne vrai si l'utilisateur en session peut effectuer cette action, faux sinon. Certaines actions demandent à nouveau de s'authentifier.
 * @return bool
 */
function current_user_can(): bool
{
    return false;
}
