<?php

/**
 * Les fonctions pour télécharger,manipuler les fichiers sources téléchargés depuis Youtube
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

/**
 * Télécharges une vidéo depuis l'url Youtube $url et l'enregistre sur $download_path
 * @throws Exception Si le téléchargement a échoué.
 * @param DownloadRequest $url La demande de téléchargement
 * @param string $download_path Optional Default PATH_SOURCES Le path où enregistrer la vidéo sur le disque.
 * @return SplFileInfo Un wrapper du fichier téléchargé
 */
function download(DownloadRequest $download_request, string $download_path = PATH_DOWNLOADS): SplFileInfo
{

    //Valider l'url
    if (!is_valid_url($download_request->url)) {
        throw new \Exception("L'url renseignée n'est pas une url valide.");
    }

    //Ajouter une progression pour l'utilisateur.

    $yt = new YoutubeDl();

    $collection = $yt->download(
        Options::create()
            ->downloadPath($download_path)
            ->url($download_request->url)
    );

    foreach ($collection->getVideos() as $video) {
        if ($video->getError() !== null) {
            throw new \Exception("Error downloading video: {$video->getError()}.");
        } else {
            $result = $video->getFile();
        }
    }

    return $result;
}
