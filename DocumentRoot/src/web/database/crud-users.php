<?php

/**
 * Fonctions CRUD users
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../database/connection.php';
/**
 * Insère un utilisateur en base de données
 */
function insert_user(User $user)
{

    $db = connect_to_db();
}
