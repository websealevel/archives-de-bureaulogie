<?php

/**
 * Gere requete AJAX pour creer un clip
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
 * Traite la requête AJAX/formulaire de génération d'un extrait
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_clip_source()
{
    echo 'api_clip_source';
}
