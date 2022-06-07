<?php

/**
 * Gere requete AJAX pour valider le formulaire de téléchargement d'une vidéo source
 * et enregistrer une demande de téléchargement
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Vendor
 */
require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Models
 */
require_once __DIR__ . '/../../models/DonwloadRequest.php';
require_once __DIR__ . '/../../models/enumDownloadState.php';

/**
 * Functions
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../database/repository-downloads.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

/**
 * Traite la requête AJAX/formulaire de téléchargement de vidéo source. Lance le téléchargement si tout est ok, retourne une erreur sinon
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_download_source()
{
    if (session_status() == PHP_SESSION_NONE) {
        write_log('start session');
        $result = session_start();
        write_log($result);
    }

    return json_encode('ok');
    exit;

    //Check le token (validité)

    //Utilisateur authentifié et capacité 'add_source' vérifiée

    //Check le form
    $input_validations = check_download_request_form();

    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 403,
            'errors' => $invalid_inputs,
        ));
        exit;
    }

    //Lancement du téléchargement de la source

    $download_request = new DownloadRequest(
        $input_validations['source_url']->value,
        $input_validations['series']->value,
        $input_validations['name']->value,
    );

    check_download_request($download_request);

    $authentificated_user_id = from_session('account_id');

    $filename = format_to_source_file($download_request);

    $response = create_download($download_request, $authentificated_user_id);

    //En cas d'erreur d'accès à la base.
    if ($response instanceof Notice) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 403,
            'errors' => array($response),
        ));
        exit;
    }

    $download_id = $response;

    //En cas de formulaire valide, on lance le téléchargement

    $yt = new YoutubeDl();
    $yt->setBinPath(from_env('PATH_BIN_YOUTUBEDL'));
    $yt->setPythonPath(from_env('PATH_PYTHON'));

    try {

        //Lancer le téléchargement et écrire la progression en base.
        $db = connect_to_db();

        //Show progress
        $yt->onProgress(static function (?string $process_target, ?string $percentage, ?string $size, ?string $speed, string $eta, ?string $total_time) use ($download_id, $db): void {
            sql_update_download($db, $download_id, $process_target, $percentage, $size, $speed, $total_time);
        });

        //Mettre l'état du download à actif
        download_change_state($download_id, DownloadState::Downloading);

        error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Downloading);

        $collection = $yt->download(
            Options::create()
                ->downloadPath(PATH_SOURCES)
                ->url($download_request->url)
                ->format(youtube_dl_download_format())
                ->output($filename)
        );

        foreach ($collection->getVideos() as $video) {

            if ($video->getError() !== null) {

                //Mettre le state du dl à failed
                download_change_state($download_id, DownloadState::Failed);

                error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Failed);

                throw new Exception("Error downloading video: {$video->getError()}");
            } else {
                //Mettre le state du dl a downloaded
                download_change_state($download_id, DownloadState::Downloaded);

                //Log un message propre sur le download terminé.
                error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Downloaded);

                //Mettre à jour le fichier source.

                //Générer le label à partir de series+slug
                $file = $video->getFile();
                exit;
            }
        }
    } catch (Exception $e) {
        error_log($e);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 500,
            'errors' => array(new Notice('Une erreur est survenue, veuillez réessayer', NoticeStatus::Error)),
        ));
        exit;
    }

    return;
}
