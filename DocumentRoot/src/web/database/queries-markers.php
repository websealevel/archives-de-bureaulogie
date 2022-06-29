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
 * @param DownloadRequest $download_request La demande de téléchargement (url, metadonnées)
 * @param string $filename Le nom du fichier (contruit à partir des métadonnées)
 * @param string $format Les formats audio/vidéo demandés pour le téléchargement
 * @param string $account_id L'identifiant du compte enregistrant le téléchargement
 * @return int L'id du téléchargement enregistré
 */
function sql_insert_marker(string $source_name, int $account_id, int $position_in_sec)
{
    $db = connect_to_db();


    // cm_source_name VARCHAR (255) NOT NULL,
    // cm_account_id INT NOT NULL,
    // cm_position_in_sec INT NOT NULL,

    $sql = 'INSERT INTO clip_markers(
            source_name, 
            account_id, 
            position_in_sec
            )
            VALUES(
            :source_name,
            :account_id,
            :position_in_sec)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':source_name', $source_name);
    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':position_in_sec', $position_in_sec);
    $stmt->execute();

    return $db->lastInsertId('clip_markers_id_seq');
}
