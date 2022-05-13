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


function create_download(DownloadRequest $download_request, string $account_id)
{

    $filename = format_to_source_file($download_request);
    $format = youtube_dl_download_format();

    try {
        $id = sql_insert_download($download_request, $filename, $format, $account_id);
    } catch (PDOException $e) {
        error_log($e);
        redirect('/download-source', 'notices', array(
            new Notice(
                sprintf("La demande téléchargement du fichier %s n'a pas pu être enregistrée ", $filename),
                NoticeStatus::Error
            )
        ));
    }

    return $id;
}
