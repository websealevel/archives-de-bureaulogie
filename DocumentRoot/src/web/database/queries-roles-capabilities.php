<?php

/**
 * L'ensemble des reqûetes sur les roles et capabilities
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/connection.php';

/**
 * Cherche l'ID d'un role par son nom
 * @param string $role Le nom du role
 * @return stdClass L'id du role ou false si le role n'existe pas.
 */
function sql_find_role_id_by_name(string $role): stdClass
{
    $db = connect_to_db();
    $sql = 'SELECT role_id FROM roles where role = :role';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':role', $role);
    $stmt->execute();
    return $result = $stmt->fetchObject();
}
