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
 * L'extension que doit avoir les fichiers sources
 */
define('EXTENSION_SOURCE', 'mp4');

/**
 * L'extension que doit avoir les fichiers extraits
 */
define('EXTENSION_CLIP', 'mp4');


/**
 * Format d'un timecode valide
 */
define('FORMAT_TIMECODE', "[0-9]{2}.[0-9]{2}.[0-9]{2}.[0-9]{3}");

/**
 * Format d'un nom de fichier extrait (vidéo)
 */
define('FORMAT_FILE_VIDEO_CLIP', "[a-z0-9\-]+[-]{2}[a-z0-9\-]+[-]{2}[0-9]{2}." . FORMAT_TIMECODE . "[-]{2}" . FORMAT_TIMECODE . "." . EXTENSION_CLIP);

/**
 * Format d'un nom de fichier source (vidéo)
 */
define('FORMAT_FILE_VIDEO_SOURCE', "[a-z0-9\-]+[-]{2}[a-z0-9\-]+." . EXTENSION_SOURCE);

/**
 * Options d'encodage video et audio pour la génération d'extraits
 */
define(
    'ENCODING_OPTIONS',
    array(
        'video' => array(
            'fps' => 60,
            'definition' => '720p'
        ),
        'audio' => array(
            'kbps' => 128
        )
    )
);
