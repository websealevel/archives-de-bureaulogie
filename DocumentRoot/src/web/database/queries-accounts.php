<?php

/**
 * L'ensemble des reqûetes sur les comptes utilisateurs
 *
 * @link
 *
 * @package wsl 
 */

function sql_insert_account(string $pseudo, string $password, string $email)
{
    $db = connect_to_db();

    $sql = 'INSERT INTO accounts(pseudo, password, email, created_on, has_reached_majority, has_accepted_the_chart, major, option, grade  )'
        . 'VALUES(:pseudo,:password,:email,:created_on, :has_reached_majority, :has_accepted_the_chart, :major, :option, :grade )';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':created_on', date('Y-m-d H:i:s'));
    $stmt->bindValue(':has_reached_majority', true);
    $stmt->bindValue(':has_accepted_the_chart', true);
    $stmt->bindValue(':major', 'cable_managment');
    $stmt->bindValue(':option', '');
    $stmt->bindValue(':grade', 'studentL1');

    $stmt->execute();

    return $stmt->lastInsertId('user_id');
}



/**
 * Cherche un compte utilisateur par pseudo
 * @param string $pseudo
 * @return un objet account
 * @throws PDOException $e
 */
function sql_find_account_by_pseudo(string $pseudo)
{

    $db = connect_to_db();
    $sql = 'SELECT user_id,pseudo, password FROM accounts where pseudo = :pseudo ';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->execute();

    return $result = $stmt->fetchObject();
}
