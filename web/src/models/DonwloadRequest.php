<?php

/**
 * Structure de données d'une demande de téléchargement d'une vidéo youtube
 * @link
 *
 * @package wsl 
 */

/**
 * Classe servant à stocker les données nécessaires pour une demande de téléchargement de vidéo source
 * Description 
 * @see 
 */
class DownloadRequest
{

    public function __construct(

         /** @var string L'url de la vidéo à télécharger */
        public readonly string $url = "",

        /** @var string Le nom de la série à laquelle appartient la vidéo */
        public readonly string $series_name = "unknown",

        /** @var string L'identifiant de l'épisode de la série (mot, nombre)*/
        public readonly string $id = "unknown",
    ) {
    }
}
