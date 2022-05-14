<?php

/**
 * Gere requete AJAX pour valider le formulaire de téléchargement d'une vidéo source
 * et enregistrer une demande de téléchargement
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../../models/DonwloadRequest.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-downloads.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

function api_download_source()
{
    session_id($_POST['PHPSESSID']);
    session_start();

    if (!current_user_can('add_source')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 500,
            'errors' => array(new Notice(
                "Vous n'avez pas l'autorisation.",
                NoticeStatus::Error
            ))
        ));
        exit;
    }

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

    //On enregistre en base une demande associée à la session
    if (!current_user_can('add_source'))
        return new Notice("Autorisation refusée", NoticeStatus::Error);


    //Check dans le fichier source si déjà une source avec cette url

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
    //Téléchargement.
    $yt = new YoutubeDl();
    //Lancer les téléchargement et écrire la progression sur la sortie standard
    $yt->setBinPath('/var/www/html/youtube-dl/youtube-dl');
    $yt->setPythonPath('/usr/bin/python3');


    try {

        $db = connect_to_db();

        //Show progress
        $yt->onProgress(static function (?string $process_target, string $percentage, string $size, string $speed, string $eta, ?string $total_time) use ($download_id, $db): void {
            sql_update_download($db, $download_id, $process_target, $percentage, $size, $speed, $total_time);
        });

        error_log('start');

        $collection = $yt->download(
            Options::create()
                ->downloadPath('/var/www/html/sources')
                ->url($download_request->url)
                ->format(youtube_dl_download_format())
                ->output($filename)
        );

        sql_change_download_state($download_id, 'downloading');

        error_log('start');


        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                error_log("Error downloading video: {$video->getError()}.");
            } else {
                $file = $video->getFile(); 
            }
        }

        write_log('Fichier enregistré : ' . $file->);

    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 500,
            'errors' => array(new Notice('Une erreur est survenue, veuillez réessayer', NoticeStatus::Error)),
        ));
        exit;
    }
}
