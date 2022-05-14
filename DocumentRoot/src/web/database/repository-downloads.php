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
require_once __DIR__ . '/../current-user.php';


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


/**
 * Retourne la liste des téléchargements en attente d'un compte
 * @param string $account_id L'id du compte utilisateur
 * @return mixed La liste des téléchargements ou une Notice en cas d'erreur
 */
function active_downloads(string $account_id)
{

    if (!current_user_can('add_source'))
        return new Notice("Autorisation refusée", NoticeStatus::Error);

    try {
        $pending_downloads = sql_find_active_downloads($account_id);
    } catch (PDOException $e) {
        error_log($e);
        return new Notice(
            sprintf("La récupération des téléchargement en attente a échoué"),
            NoticeStatus::Error
        );
    }

    return $pending_downloads;
}

/**
 * Retourne la liste des téléchargements terminés (pas ceux en cours de téléchargement ou en attente)
 * @return array
 */
function download_history()
{

    try {
        $terminated_downloads = sql_find_all_terminated_downloads();
    } catch (PDOException $e) {
        error_log($e);
        redirect('/sign-up', 'notices', array(
            new Notice(
                sprintf("La récupération de l'historique des téléchargements a échoué"),
                NoticeStatus::Error
            )
        ));
    }

    return $terminated_downloads;
}

/**
 * Change le status du téléchargement
 * @param string $download_id L'id du téléchargement
 * @param string $new_state Le nouvel état du téléchargement
 * @return int
 */
function download_change_state(string $download_id, string $new_state): int
{

    try {
        $download_changed = sql_change_download_state($download_id, $new_state);
    } catch (PDOException $e) {
        error_log($e);
        return new Notice(
            sprintf("Le changement de statut du téléchargement a échoué."),
            NoticeStatus::Error
        );
    }

    return $download_changed;
}
