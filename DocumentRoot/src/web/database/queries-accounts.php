<?php

/**
 * L'ensemble des reqûetes sur les comptes utilisateurs
 *
 * @link
 *
 * @package wsl 
 */


/**
 * Insert un compte utilisateur
 * @param string $pseudo Le pseudo de l'utilisateur
 * @param string $password Le password hashé
 * @param string $email
 * @return int L'id du compte crée
 * @throws PDOException $e
 */
function sql_insert_account(string $pseudo, string $password, string $email)
{
    $db = connect_to_db();

    $sql = 'INSERT INTO accounts(
            pseudo, 
            password, 
            email, 
            created_on, 
            has_reached_majority, 
            has_accepted_the_chart, 
            heard_about_bureaulogy)

            VALUES(
            :pseudo,
            :password,
            :email,
            :created_on, 
            :has_reached_majority, 
            :has_accepted_the_chart, 
            :heard_about_bureaulogy)';

    $stmt = $db->prepare($sql);


    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':created_on', date('Y-m-d H:i:s'));
    $stmt->bindValue(':has_reached_majority', true);
    $stmt->bindValue(':has_accepted_the_chart', true);
    $stmt->bindValue(':heard_about_bureaulogy', 'tribunal_des_bureaux');

    $stmt->execute();

    return $db->lastInsertId('accounts_id_seq');
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

    $sql =
        'SELECT 
        id,pseudo,password 
        FROM accounts 
        where pseudo = :pseudo ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->execute();

    return $result = $stmt->fetchObject();
}
