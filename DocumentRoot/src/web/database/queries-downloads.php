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
require_once __DIR__ . '/../../models/enumDownloadState.php';

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
    $stmt->bindValue(':account_id', intval($account_id));
    $stmt->bindValue(':state', DownloadState::Pending->value);
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
 * Retourne la liste des téléchargements en cours, faux sinon
 * @return array|bool
 */
function sql_find_active_downloads(): array|bool
{

    $db = connect_to_db();

    $sql =
        'SELECT 
        id,url,format,filename,progression,speed
        FROM downloads 
        WHERE state = :state';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':state', DownloadState::Downloading->value);
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
        id,url,format,filename,created_on,account_id,state,totaltime
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

/**
 * Change l'état d'un téléchargement.
 * @param string $download_id L'id du téléchargement.
 * @param DownloadState $state Le nouvel état du téléchargement.
 * @return int Le nombre de lignes modifiées.
 */
function sql_change_download_state(string $download_id, DownloadState $state): int
{
    $db = connect_to_db();

    $sql =
        'UPDATE downloads 
        SET state = :state
        WHERE id = :id
        ';
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':state', $state->value);
    $stmt->bindValue(':id', $download_id);
    $stmt->execute();

    // return the number of row affected
    return $stmt->rowCount();
}

/**
 * Met à jour les métadonnées d'un téléchargement. On passe la connexion à la DB pour éviter d'en ouvrir une à chaque fois
 * @param PDO $db L'accès à la base
 * @param string $download_id L'identifiant du téléchargement à mettre à jour
 */
function sql_update_download(PDO $db, string $download_id, ?string $progressTarget, string $percentage, string $size, string $speed, ?string $total_time)
{
    $sql =
        'UPDATE downloads
    SET 
    progression = :progression,
    speed = :speed,
    totaltime = :totaltime
    WHERE
    id = :id';

    $stmt = $db->prepare($sql);
    $stmt->bindValue(':progression', $percentage);
    $stmt->bindValue(':speed', $speed);
    $stmt->bindValue(':totaltime', $total_time ?? '');
    $stmt->bindValue(':id', $download_id);
    $stmt->execute();

    // return the number of row affected
    return $stmt->rowCount();
}
