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

/**
 * Retourne vrai si une demande de téléchargement en attente sur la même url est déjà enregistrée, faux sinon
 * @param string $url L'url de la vidéo à télécharger
 * @return stdClass|false
 */
function sql_find_pending_download_request_with_same_url(string $url): stdClass|false
{

    $db = connect_to_db();

    $sql =
        'SELECT 
        id
        FROM downloads 
        where url = :url
        AND
        state = :state ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':url', $url);
    $stmt->bindValue(':state', 'pending');
    $stmt->execute();

    return $result = $stmt->fetchObject();
}

/**
 * Retourne la liste des téléchargements en attente d'un compte, faux sinon
 * @param string $account_id L'id du compte
 * @return array|bool
 */
function sql_find_pending_downloads(string $account_id): array|bool
{

    $db = connect_to_db();

    $sql =
        'SELECT 
        id,url,format,filename
        FROM downloads 
        where account_id = :account_id
        AND
        state = :state ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':account_id', $account_id);
    $stmt->bindValue(':state', 'pending');
    $stmt->execute();

    return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retourne tous les téléchargements terminés (not pending et not downloading), avec succès ou non
 * @return array|bool La liste des téléchargements terminés, faux si aucun
 */
function sql_find_all_terminated_downloads(): array|bool
{

    $db = connect_to_db();

    $sql =
        'SELECT 
        id,url,format,filename,created_on
        FROM downloads 
        where state = :state_1
        OR
        state = :state_2 ';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':state_1', 'downloaded');
    $stmt->bindValue(':state_2', 'failed');
    $stmt->execute();

    return $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
