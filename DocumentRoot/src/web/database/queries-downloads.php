<?php

/**
 * Les requêtes sur les downloads
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/connection.php';
require_once __DIR__ . '/../../models/DonwloadRequest.php';

/**
 * Enregistre une demande de téléchargement
 * @param DownloadRequest $download_request La demande de téléchargement (url, metadonnées)
 * @param string $filename Le nom du fichier (contruit à partir des métadonnées)
 * @param string $format Les formats audio/vidéo demandés pour le téléchargement
 * @param string $account_id L'identifiant du compte enregistrant le téléchargement
 * @return int L'id du téléchargement enregistré
 */
function sql_insert_download(DownloadRequest $download_request, string $filename, string $format, string $account_id)
{
    $db = connect_to_db();

    $sql = 'INSERT INTO downloads(
            url, 
            filename, 
            format, 
            account_id, 
            state,
            created_on)

            VALUES(
            :url,
            :filename,
            :format,
            :account_id, 
            :state, 
            :created_on)';

    $stmt = $db->prepare($sql);

    $stmt->bindValue(':url', $download_request->url);
    $stmt->bindValue(':filename', $filename);
    $stmt->bindValue(':format', $format);
    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':state', 'pending');
    $stmt->bindValue(':created_on', date('Y-m-d H:i:s'));
    $stmt->execute();

    return $db->lastInsertId('downloads_id_seq');
}
