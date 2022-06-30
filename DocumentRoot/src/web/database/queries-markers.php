<?php

/**
 * Les requêtes sur les marqueurs
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/connection.php';


/**
 * Enregistre une demande de téléchargement
 * @param string $source_name Le nom du fichier source visé par le marqueur
 * @param int $account_id L'id du compte de l'utilisateur qui enregistre le marqueur
 * @param int $position_in_sec La position (s) du marqueur sur la timeline de la vidéo source
 * @return string|false
 */
function sql_insert_marker(string $source_name, int $account_id, int $position_in_sec, bool $is_shareable = false): string|false
{
    $db = connect_to_db();

    $sql = 'INSERT INTO clip_markers(
            source_name, 
            account_id, 
            position_in_sec,
            is_shareable,
            require_authentification_check
            )
            VALUES(
            :source_name,
            :account_id,
            :position_in_sec,
            :is_shareable,
            :require_authentification_check)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':source_name', $source_name);
    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':position_in_sec', $position_in_sec);
    $stmt->bindValue(':is_shareable', 'false');
    $stmt->bindValue(':require_authentification_check', 'false');
    $stmt->execute();

    return $db->lastInsertId('clip_markers_id_seq');
}

/**
 * Supprime un marqueur.
 * @param string $source_name Le nom du fichier source visé par le marqueur
 * @param int $account_id L'id du compte de l'utilisateur qui supprime le marqueur
 */
function sql_delete_marker(string $source_name, int $account_id, int $position_in_sec): int
{

    $db = connect_to_db();

    $sql = 'DELETE FROM clip_markers WHERE account_id = :account_id AND source_name = :source_name AND position_in_sec = :position_in_sec';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':source_name', $source_name);
    $stmt->bindValue(':position_in_sec', $position_in_sec);

    $stmt->execute();

    return $stmt->rowCount();
}

/**
 * Retrouve les marqueurs d'une source appartenant à l'utilisateur.
 * @param string $source_name Le nom du fichier source visé par le marqueur
 * @param int $account_id L'id du compte de l'utilisateur qui possède les marqueurs
 */
function sql_find_markers_on_source_by_account_id(string $source_name, int $account_id): array
{

    $db = connect_to_db();

    $sql = 'SELECT id,position_in_sec FROM clip_markers WHERE account_id = :account_id AND source_name = :source_name';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':source_name', $source_name);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
