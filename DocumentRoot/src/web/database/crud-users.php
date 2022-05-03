<?php

/**
 * Fonctions CRUD users
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../router/router.php';
/**
 * Insère un utilisateur en base de données
 * @param User $user
 * @return string|bool L'id de l'utilisateur inséré
 */
function create_account(User $user): string|bool
{

    $db = connect_to_db();

    $sql = 'INSERT INTO accounts(pseudo, password, email, created_on)'
        . 'VALUES(:pseudo,:password,:email,:created_on)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':pseudo', $user->pseudo);
    $stmt->bindValue(':password', $user->pseudo);
    $stmt->bindValue(':email', $user->pseudo);
    $stmt->bindValue(':created_on', date('Y-m-d H:i:s'));

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        $_SESSION['notices'] = array(
            new Notice("Un membre de l'Université Libre de Bureaulogie porte déjà ce pseudonyme ou dispose déjà de cet email. Veuillez en essayer un autre s'il vous plaît", NoticeStatus::Error)
        );

        redirect('/sign-up');
    }

    return $db->lastInsertId();
}
