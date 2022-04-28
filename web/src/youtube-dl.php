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
 * @see https://github.com/ytdl-org/youtube-dl/blob/master/README.md#format-selection=
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

    //Valider le nom de domaine
    if (!is_url_domain_authorized($download_request->url)) {
        throw new \Exception("Vous essayez de télécharger une vidéo depuis un nom de domaine non autorisé, no f****** way.");
    }

    //Préparer le format du fichier pour qu'il soit source compatible.


    if (!are_download_request_user_input_valid($download_request)) {
        throw new \Exception("Les métadonnées associées à la vidéo source contiennent des caractères illégaux. Merci de soumettre des chaînes de caractères ne comprenant que des caractères alphanumériques.");
    }

    $file_name = format_to_source_file($download_request);

    //Ajouter une progression pour l'utilisateur.

    $yt = new YoutubeDl();

    $format = youtube_dl_download_format();

    $collection = $yt->download(
        Options::create()
            ->downloadPath($download_path)
            ->url($download_request->url)
            ->format($format)
            ->output($file_name)
            ->audioFormat('mp3')
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

/**
 * Retourne un format vidéo/audio intérprétable par youtube-dl pour le téléchargement de la vidéo
 * @see https://github.com/ytdl-org/youtube-dl/blob/master/README.md#format-selection
 * @return string
 */
function youtube_dl_download_format()
{
    //Relire la doc ici

    $video = sprintf("bestvideo[height<=%s]/bestvideo[ext=mp4]/best", ENCODING_OPTION_VIDEO_HEIGHT);

    $audio = "best[ext=mp3]/bestaudio";

    $format = "{$video}";

    return $format;
}
