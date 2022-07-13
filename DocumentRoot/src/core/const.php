<?php

/**
 * Configurations de l'application core
 *
 * @package wsl 
 */


/**
 * Charge l'autoload.php de composer pour inclure les dépendances vendor du script appelant.
 * @return void
 */
function autload_core(): void
{
    require_once __DIR__ . '/../../vendor/autoload.php';
}

/**
 * Nombre max de caractères dans un tweet (2022)
 */
define('TWEET_NB_MAX_CHARACTERS', 280);


/**
 * Taille maximale d'un clip vidéo (s) (définie par Twitter en 2022)
 */
define('MAX_CLIP_DURATION_IN_SEC', 140);

/**
 * Le fichier source (contient les déclarations des extraits)
 * @const SOURCE_FILE Le fichier source
 */
define('SOURCE_FILE', __DIR__ . '/extraits.xml');

/**
 * Le schéma de validation du fichier source (DTD).
 */
define('SOURCE_FILE_DTD', __DIR__ . '/extraits.dtd');

/**
 * Le dossier contenant les vidéos sources
 */

define('DIR_SOURCES', 'sources');

/**
 * Le dossier contenant les vidéos extraits
 */

define('DIR_CLIPS', 'extraits');

/**
 * Le path des vidéos sources
 */
define('PATH_SOURCES', __DIR__ . '/../../' . DIR_SOURCES);


/**
 * Le path des extraits
 */
define('PATH_CLIPS', __DIR__ . '/../../' . DIR_CLIPS);

/**
 * Le namespace du fichier source.
 */
define('XMLNS_SOURCE_FILE', 'https://archives-de-bureaulogie.fr/clips');

/**
 * L'extension que doit avoir les fichiers sources
 */
define('EXTENSION_SOURCE', 'mp4');

/**
 * L'extension que doit avoir les fichiers extraits
 */
define('EXTENSION_CLIP', 'mp4');

/**
 * Format de la piste audio d'une vidéo source téléchargée
 */
define('EXTENSION_AUDIO', 'm4a');

/**
 * Format valide d'un timecode 
 */
define('FORMAT_TIMECODE', "[0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{3}");

/**
 * Format valide du nom de la source dans le nom du fichier extrait
 */
define('FORMAT_SOURCE_NAME_VIDEO_CLIP', '[a-z0-9\-\.]+');

/**
 * Format valide du nom du slug dans le nom du fichier extrait
 */
define('FORMAT_SLUG_VIDEO_CLIP', '[a-z0-9\-]+');


/**
 * Format valide d'un nom de fichier clip (extrait vidéo)
 * @const FORMAT_FILE_VIDEO_CLIP
 */
define(
    'FORMAT_FILE_VIDEO_CLIP',
    FORMAT_SOURCE_NAME_VIDEO_CLIP . '[-]{2}from[-]{1}' . FORMAT_TIMECODE . '[-]{1}to[-]{1}' . FORMAT_TIMECODE . '\.' . EXTENSION_CLIP
);

/**
 * Format valide d'un nom de fichier source (vidéo)
 * @const FORMAT_FILE_VIDEO_SOURCE
 */
define(
    'FORMAT_FILE_VIDEO_SOURCE',
    sprintf(
        "[a-z0-9\-]+[-]{1}[a-z0-9\-]+.%s",
        EXTENSION_SOURCE
    )
);

/**
 * Options d'encodage video et audio pour la génération d'extraits
 */

/**
 * Kbps du flux vidéo
 */
define('ENCODING_OPTION_VIDEO_KBPS', 369);

/**
 * Kbps du flux audio
 */
define('ENCODING_OPTION_AUDIO_KBPS', 96);

/**
 * Sampling rate audio Hz
 */
define('ENCODING_OPTION_AUDIO_SAMPLING_RATE', 48000);

/**
 * Hauteur max vidéo source et extrait (en p)
 */
define('ENCODING_OPTION_VIDEO_HEIGHT', '1024');

/**
 * La liste des domaines (hosts) depuis lesquels l'application autorise à télécharger une source.
 */
define('ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM', array(
    'www.youtube.com',
    'youtube.com'
));
/**
 * La liste des series possibles pour (formatage du nom des vidéos sources)
 */
define('SOURCE_SERIES', array(
    'le-tribunal-des-bureaux'
));

/**
 * Retourne vrai si php est utilisé en mode cli, faux sinon
 * @return bool
 * @see https://www.php.net/manual/en/function.php-sapi-name.php 
 */
function is_cli(): bool
{
    return 'cli' === php_sapi_name();
}

/**
 * Définit les code format pour le téléchargement des vidéos depuis youtube (youtube-dl)
 * @see https://github.com/ytdl-org/youtube-dl/blob/master/README.md#format-selection
 */
define('CODE_FORMAT_VIDEO_MP4_YOUTUBE', array(
    '720p' => 136,
    '1080p' => 137
));

define('CODE_FORMAT_AUDIO_M4E', 140);
