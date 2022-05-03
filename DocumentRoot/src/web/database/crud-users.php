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
 * @param User $user
 * @return string|bool L'id de l'utilisateur inséré
 */
function insert_user(User $user): string|bool
{

    $db = connect_to_db();


    $sql = 'INSERT INTO accounts(pseudo, password, email, created_on)'
        . 'VALUES(:pseudo,:password,:email,:created_on)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':pseudo', $user->pseudo);
    $stmt->bindValue(':password', $user->pseudo);
    $stmt->bindValue(':email', $user->pseudo);
    $stmt->bindValue(':created_on',date('Y-m-d H:i:s'));


    $stmt->execute();

    return $db->lastInsertId();
}
