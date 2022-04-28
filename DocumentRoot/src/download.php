<?php

/**
 * Les fonctions pour télécharger, manipuler les fichiers sources téléchargés depuis Youtube
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

    //Valider les métadonnées de la vidéo a télcharger pour valider le nom du fichier
    if (!are_download_request_user_input_valid($download_request)) {
        throw new \Exception("Les métadonnées associées à la vidéo source sont vides ou contiennent des caractères illégaux. Merci de soumettre des chaînes de caractères ne comprenant que des caractères alphanumériques.");
    }

    //Préparer le format du fichier pour qu'il soit source compatible.
    $file_name = format_to_source_file($download_request);

    //Téléchargement.
    $yt = new YoutubeDl();

    $format = youtube_dl_download_format();

    $collection = $yt->download(
        Options::create()
            ->downloadPath($download_path)
            ->url($download_request->url)
            ->format($format)
            ->output($file_name)
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
function youtube_dl_download_format(): string
{
    //Si on combine video et audio avec le '+', youtube-dl télécharge deux fichiers puis ffmpeg ou avconv fusionne les deux fichiers ensuite dans un format container (mp4 ou mkv).

    //Le format m4e est équivalent au format mp4 pour un fichier audio.

    $format = sprintf(
        "bestvideo[height <=? %s][tbr<%s][ext=%s]+bestaudio[height <=? %s][ext=%s][asr <= %s]/best",
        ENCODING_OPTION_VIDEO_HEIGHT,
        ENCODING_OPTION_VIDEO_KBPS,
        EXTENSION_SOURCE,
        ENCODING_OPTION_VIDEO_HEIGHT,
        EXTENSION_AUDIO,
        ENCODING_OPTION_AUDIO_SAMPLING_RATE
    );

    return $format;
}