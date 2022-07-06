<?php

/**
 * L'ensemble des reqûetes sur les comptes utilisateurs
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/queries-roles-capabilities.php';

/**
 * Insert un compte utilisateur
 * @param string $pseudo Le pseudo de l'utilisateur
 * @param string $password Le password hashé
 * @param string $email
 * @return int L'id du compte crée
 * @throws PDOException $e
 */
function sql_insert_account(string $pseudo, string $password, string $email, string $role = 'contributeur')
{
    $db = connect_to_db();

    $result = sql_find_role_id_by_name($role);

    if (!$result) {
        throw new Exception(sprintf("Le role %s n'existe pas", $role));
    }

    $sql = 'INSERT INTO public.accounts(
            pseudo, 
            password, 
            email, 
            created_on, 
            has_reached_majority, 
            has_accepted_the_chart, 
            heard_about_bureaulogy,
            role_id)

            VALUES(
            :pseudo,
            :password,
            :email,
            :created_on, 
            :has_reached_majority, 
            :has_accepted_the_chart, 
            :heard_about_bureaulogy,
            :role_id)';

    $stmt = $db->prepare($sql);


    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->bindValue(':password', $password);
    $stmt->bindValue(':role_id', $password);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':created_on', date('Y-m-d H:i:s'));
    $stmt->bindValue(':has_reached_majority', true);
    $stmt->bindValue(':has_accepted_the_chart', true);
    $stmt->bindValue(':heard_about_bureaulogy', 'tribunal_des_bureaux');
    $stmt->bindValue(':role_id', $result->role_id);

    $stmt->execute();

    return $db->lastInsertId('accounts_id_seq');
}



/**
 * Cherche un compte utilisateur par pseudo. Retourne le compte, faux sinon
 * @param string $pseudo
 * @return mixed
 * @throws PDOException $e
 */
function sql_find_account_by_pseudo(string $pseudo)
{

    $db = connect_to_db();

    $sql =
        'SELECT 
        id,pseudo,password 
        FROM public.accounts 
        where pseudo = :pseudo ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':pseudo', $pseudo);
    $stmt->execute();

    return $result = $stmt->fetchObject();
}

/**
 * Cherche un compte par id
 * @param string $id
 * @return stdClass|false
 */
function sql_find_account_by_id(string $id): stdClass|false
{

    $db = connect_to_db();
    $sql = 'SELECT id, pseudo, password FROM public.accounts where id = :id';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    return $result = $stmt->fetchObject();
}

/**
 * Cherche le role d'un compte par id du compte
 * @param string $id L'id du compte
 * @return string
 */
function sql_find_role_of_account(string $id): string
{

    $db = connect_to_db();

    $sql_role_id = 'SELECT role_id FROM public.accounts where id = :id';
    $stmt = $db->prepare($sql_role_id);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $result_role_id = $stmt->fetchObject();

    $role_id = $result_role_id->role_id;

    $sql_role_name = 'SELECT role FROM roles where role_id = :role_id';
    $stmt = $db->prepare($sql_role_name);
    $stmt->bindValue(':role_id', $role_id);
    $stmt->execute();

    $result_role_name = $stmt->fetchObject();

    return $result_role_name->role;
}
