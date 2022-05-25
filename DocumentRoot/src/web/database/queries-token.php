<?php

/**
 * CRUD des tokens pour consommer l'API Ajax
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/connection.php';


/**
 * Insere un token API pouir le compte utilisateur
 * @param string $value La valeur du token (chaine de caractres aléatoire)
 * @param string $account L'id du compte auquel le token est associé
 * @param int $expiration_in_seconds Le temps de validité du token Optional Default 3600s
 * @return int L'id du token crée
 * @throws PDOException $e
 */
function sql_insert_token(string $value, string $account, int $expiration_in_seconds = 3600)
{

    $db = connect_to_db();

    $sql = 'INSERT INTO tokens(
            value, 
            account_id, 
            expiration_in_seconds, 
            created_on)

            VALUES(
                :value,
                :account_id,
                :expiration_in_seconds,
                :created_on
            )';

    $stmt = $db->prepare($sql);


    $stmt->bindValue(':value', $value);
    $stmt->bindValue(':account_id', $account);
    $stmt->bindValue(':expiration_in_seconds', $expiration_in_seconds);
    $stmt->bindValue(':created_on', time());

    $stmt->execute();

    return $db->lastInsertId('tokens_id_seq');
}
