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
 * @param string $url L'url de la vidéo Youtube à télécharger
 * @param string $download_path Optional Default PATH_SOURCES Le path où enregistrer la vidéo sur le disque.
 * @return SplFileInfo Un wrapper du fichier téléchargé
 */
function download(string $url, string $download_path = PATH_DOWNLOADS): SplFileInfo
{

    //Ajouter une progression pour l'utilisateur.

    $yt = new YoutubeDl();

    $collection = $yt->download(
        Options::create()
            ->downloadPath($download_path)
            ->url($url)
    );

    //Formatter le nom du fichier
    //On a demandé à l'utilisateur
    //-nom de la série -numero/identifiant

    foreach ($collection->getVideos() as $video) {
        if ($video->getError() !== null) {
            throw new Error("Error downloading video: {$video->getError()}.");
        } else {
            $result = $video->getFile();
        }
    }

    return $result;
}
