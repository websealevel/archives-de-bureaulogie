<?php

/**
 * Toutes les fonctions checkant et gérant l'état de l'user courant ainsi que ses droits
 *
 * @package wsl 
 */

/**
 * Retourne vrai si l'utilisateur courant est connecté, faux sinon
 * @return bool
 */
function is_current_user_logged_in(): bool
{
    if (!isset($_SESSION))
        return false;

    return boolval($_SESSION['user_authentificated']);
}
