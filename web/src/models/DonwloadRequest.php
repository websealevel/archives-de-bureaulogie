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
        public string $url = "",
        public string $series_name = "unknown",
        public string $identifiant = "unknown",
    ) {
    }
}
