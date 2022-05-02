<?php

/**
 * Fonctions CRUD users
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/User.php';

/**
 * Insère un utilisateur en base de données
 */
function insert_user(User $user)
{

    write_log($user);
}
