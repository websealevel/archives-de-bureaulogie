<?php

/**
 * Gere requete AJAX pour télécharger une vidéo source
 * Ouvre un websocket
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../actions/download-source.php';
require_once __DIR__ . '/../../models/DonwloadRequest.php';
require_once __DIR__ . '/../../core/download.php';
require_once __DIR__ .'/../utils.php';

autoload();

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

function api_download_source()
{
    session_id($_POST['PHPSESSID']);
    session_start();

    if (!current_user_can('add_source')) {
        echo 'Autorisation refusée';
        exit;
    }

    //Check le form
    $input_validations = check_download_request_form();

    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {
        print_r($invalid_inputs);
        exit;
    }

    $download_request = new DownloadRequest(
        $input_validations['source_url']->value,
        $input_validations['series']->value,
        $input_validations['name']->value,
    );

    //Lancement du téléchargement de la source
    check_download_request($download_request);

    //Préparer le format du fichier pour qu'il soit source compatible.
    $file_name = format_to_source_file($download_request);

    echo 'ok';
    exit;

    // //Téléchargement.
    // $yt = new YoutubeDl();

    // //Show progress
    // $yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
    //     echo "Download file: $progressTarget; Percentage: $percentage; Size: $size";
    //     if ($speed) {
    //         echo "; Speed: $speed";
    //     }
    //     if ($eta) {
    //         echo "; ETA: $eta";
    //     }
    //     if ($totalTime !== null) {
    //         echo "; Downloaded in: $totalTime";
    //     }
    // });


    // $yt->setBinPath('/var/www/html/youtube-dl/youtube-dl');
    // $yt->setPythonPath('/usr/bin/python3');

    // $format = youtube_dl_download_format();

    // //Téléchargement
    // $collection = $yt->download(
    //     Options::create()
    //         ->downloadPath('/var/www/html/sources')
    //         ->url($download_request->url)
    //         ->format($format)
    //         ->output($file_name)
    // );


    // try {
    //     foreach ($collection->getVideos() as $video) {
    //         if ($video->getError() !== null) {
    //             throw new \Exception("Error downloading video: {$video->getError()}.");
    //         } else {
    //             $result = $video->getFile();
    //         }
    //     }
    //     return $result;
    // } catch (Exception $e) {
    //     error_log($e);
    //     //Dire a l'utilisateur que le téléchargement a échoué et qu'il doit réessayer.
    //     echo 'Le téléchargement a échoué. Veuillez réessayer.';
    //     //Nettoyer les données temporaires de téléchargement.
    // }

    // echo 'Téléchargement terminé';
    // exit;
}


