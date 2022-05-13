<?php

/**
 * Fonctions CRUD Downloads
 * Gestion et historique des downloads
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../../core/download.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/queries-downloads.php';


/**
 * Enregistre une demande de téléchargement. Retourne l'id de la demande en cas de succès, une notice en cas d'erreur
 * @param DownloadRequest $downlad_request La demande de téléchargement
 * @param string $account_id L'id du compte faisant la demande
 * @return int|Notice
 */
function create_download(DownloadRequest $download_request, string $account_id): int|Notice
{

    $filename = format_to_source_file($download_request);
    $format = youtube_dl_download_format();

    if (false !== sql_find_pending_download_request_with_same_url($download_request->url)) {
        return new Notice(
            sprintf("La demande téléchargement a déjà été enregistrée.", $filename),
            NoticeStatus::Error
        );
    }

    try {
        $id = sql_insert_download($download_request, $filename, $format, $account_id);
    } catch (PDOException $e) {
        error_log($e);
        return new Notice(
            sprintf("La demande téléchargement du fichier %s n'a pas pu être enregistrée ", $filename),
            NoticeStatus::Error
        );
    }

    return $id;
}
