<?php

/**
 * Les fonctions pour télécharger, manipuler les fichiers sources téléchargés depuis Youtube
 *
 * @package wsl 
 */

require_once __DIR__ . '/../web/utils.php';

autoload();

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

/**
 * Télécharges une vidéo depuis l'url Youtube $url et l'enregistre sur $download_path
 * @see https://github.com/ytdl-org/youtube-dl/blob/master/README.md#format-selection
 * @param DownloadRequest $url La demande de téléchargement
 * @param string $download_path Optional Default PATH_SOURCES Le path où enregistrer la vidéo sur le disque.
 * @return SplFileInfo|bool Un wrapper du fichier téléchargé, faux si le téléchargement échoue
 * @throws Exception Si le téléchargement a échoué.
 */
function core_download(DownloadRequest $download_request, string $download_path = PATH_SOURCES): SplFileInfo|bool
{

    check_download_request($download_request);

    //Préparer le format du fichier pour qu'il soit source compatible.
    $file_name = format_to_source_file($download_request);

    //Téléchargement. Indiquer les binaires locaux.
    $yt = new YoutubeDl();

    //Show progress
    $yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
        echo "Download file: $progressTarget; Percentage: $percentage; Size: $size";
        if ($speed) {
            echo "; Speed: $speed";
        }
        if ($eta) {
            echo "; ETA: $eta";
        }
        if ($totalTime !== null) {
            echo "; Downloaded in: $totalTime";
        }
    });

    $yt->setBinPath(YOUTUBE_DL_PATH);
    $yt->setPythonPath(PYTHON_PATH);

    $format = youtube_dl_download_format();

    $collection = $yt->download(
        Options::create()
            ->downloadPath($download_path)
            ->url($download_request->url)
            ->format($format)
            ->output($file_name)
    );

    try {
        foreach ($collection->getVideos() as $video) {
            if ($video->getError() !== null) {
                throw new \Exception("Error downloading video: {$video->getError()}.");
            } else {
                $result = $video->getFile();
            }
        }
        return $result;
    } catch (Exception $e) {
        error_log($e);
        //Dire a l'utilisateur que le téléchargement a échoué et qu'il doit réessayer.
        echo 'Le téléchargement a échoué. Veuillez réessayer.';
        //Nettoyer les données temporaires de téléchargement.
        return false;
    }
}

/**
 * Valide la demande de téléchargement.
 * @param DownloadRequest $download_request La demande de téléchargement.
 * @throws Exception - si l'url de la vidéo à télécharger n'est pas valide.
 * @throws Exception - si l'utilisateur essaie de télécharger une video depuis un host pas autorisé
 * @throws Exception - si le nom du fichier sous lequel est enregistré la vidéo contient des caractères invalides
 * @throws Exception - si un téléchargement avec la même url est déjà en cous
 * @throws Exception - si la vidéo source est déjà enregistrée dans le fichier source (extraits.xml)
 * @return void
 */
function check_download_request(DownloadRequest $download_request): void
{
    //Valider l'url
    if (!is_valid_url($download_request->url)) {
        throw new \Exception("L'url renseignée n'est pas une url valide.");
    }

    //Valider le nom de domaine
    if (!is_url_domain_authorized($download_request->url)) {
        throw new \Exception("Vous essayez de télécharger une vidéo depuis un nom de domaine non autorisé, no f***ing way !");
    }

    //Valider les métadonnées de la vidéo a télécharger pour valider le nom du fichier
    if (!is_download_request_valid($download_request)) {
        throw new \Exception("Les données envoyées contiennent des caractères illégaux. Merci de soumettre des chaînes de caractères ne comprenant que des caractères alphanumériques.");
    }

    //Valider qu'un téléchargement en cours (status downloading) sur la meme url n'existe pas
    if (already_requested($download_request)) {
        throw new \Exception("La source est déjà en cours de téléchargement.");
    }

    /**
     * Ces deux checks font partie du module en gestion des fichiers et du fichier source. Ils devront être refactor dans un module indep.
     */

    //Check que la video n'est pas déja enregistrée dans le fichier source (une source avec la même url)
    if (is_source_already_declared($download_request->series_name, $download_request->id, $download_request->url)) {
        throw new \Exception("Cette source est déjà présente dans les archives.");
    }

    //Check que la vidéo à télécharger n'a pas un nom déjà utilisé par une autre vidéo source
    if (source_exists(format_to_source_file($download_request))) {
        throw new \Exception(sprintf("Une source du nom <em>%s</em> existe déjà dans nos archives, veuillez en choisir un autre.", format_to_source_file($download_request)));
    }

    return;
}

/**
 * Retourne un format vidéo/audio intérprétable par youtube-dl pour le téléchargement de la vidéo
 * @see https://github.com/ytdl-org/youtube-dl/blob/master/README.md#format-selection
 * @return string
 */
function youtube_dl_download_format(): string
{
    $format = sprintf("%s+%s", CODE_FORMAT_VIDEO_MP4_YOUTUBE_1080P, CODE_FORMAT_AUDIO_M4E_YOUTUBE);
    return $format;
}
