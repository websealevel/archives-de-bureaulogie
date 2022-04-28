<?php

/**
 * Les constantes du projet (config)
 *
 * @package wsl 
 */


/**
 * Le fichier source (contient les déclarations des extraits)
 */
define('SOURCE_FILE', 'extraits.xml');

/**
 * Le schéma de validation du fichier source (DTD).
 */
define('SOURCE_FILE_DTD', 'extraits.dtd');

/**
 * Le namespace du fichier source.
 */
define('XMLNS_SOURCE_FILE', 'https://websealevel.com/ackboo-out-of-context');


/**
 * Le path des vidéos sources
 */
define('PATH_SOURCES', 'sources');

/**
 * Le path des extraits
 */
define('PATH_CLIPS', 'extraits');

/**
 * Le path des downloads
 */
define('PATH_DOWNLOADS', 'downloads');

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
define('EXTENSION_AUDIO','m4a');

/**
 * Format valide d'un timecode 
 */
define('FORMAT_TIMECODE', "[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{3}");

/**
 * Format valide d'un nom de fichier extrait (vidéo)
 */
define('FORMAT_FILE_VIDEO_CLIP', "[a-z0-9\-]+[-]{2}[a-z0-9\-]+[-]{2}[0-9]{2}." . FORMAT_TIMECODE . "[-]{2}" . FORMAT_TIMECODE . "." . EXTENSION_CLIP);

/**
 * Format valide d'un nom de fichier source (vidéo)
 */
define('FORMAT_FILE_VIDEO_SOURCE', "[a-z0-9\-]+[-]{2}[a-z0-9\-]+." . EXTENSION_SOURCE);

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
define('ENCODING_OPTION_VIDEO_HEIGHT', '720');

/**
 * La liste des domaines (hosts) depuis lesquels l'application autorise à télécharger une source.
 */
define('ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM', array(
    'www.youtube.com',
    'youtube.com'
));
