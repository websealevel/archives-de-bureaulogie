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
 * @param float $timecode_start_in_sec
 * @param float $timecode_end_in_sec
 * @param string $title
 * @return string|false
 */
function sql_insert_marker(string $source_name, int $account_id, float $timecode_start_in_sec, float $timecode_end_in_sec, string $title, bool $is_shareable = false): string|false
{

    $db = connect_to_db();

    $sql = 'INSERT INTO clip_markers(
            source_name, 
            account_id, 
            timecode_start_in_sec,
            timecode_end_in_sec,
            title,
            is_shareable,
            require_authentification_check
            )
            VALUES(
            :source_name,
            :account_id,
            :timecode_start_in_sec,
            :timecode_end_in_sec,
            :title,
            :is_shareable,
            :require_authentification_check)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':source_name', $source_name);
    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':timecode_start_in_sec', $timecode_start_in_sec);
    $stmt->bindValue(':timecode_end_in_sec', $timecode_end_in_sec);
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':is_shareable', 'false');
    $stmt->bindValue(':require_authentification_check', 'false');
    $stmt->execute();

    return $db->lastInsertId('clip_markers_id_seq');
}

/**
 * Supprime un marqueur.
 * @param int $account_id L'id du compte de l'utilisateur qui supprime le marqueur
 * @param int $marker_id L'id du marker
 */
function sql_delete_marker(int $account_id, int $marker_id): int
{

    $db = connect_to_db();

    $sql = 'DELETE FROM clip_markers WHERE account_id = :account_id AND id = :marker_id';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':marker_id', $marker_id);

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

    $sql = 'SELECT id,timecode_start_in_sec,timecode_end_in_sec,title FROM clip_markers WHERE account_id = :account_id AND source_name = :source_name';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':source_name', $source_name);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
