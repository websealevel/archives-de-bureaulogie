<?php

/**
 * Un wrap des métadonnées d'un fichier clip (analyse de son nom de fichier)
 *
 * @link extraits.dtd
 * @package wsl 
 */


class ClipMetaData
{
    public function __construct(
        /** @var string Le slug (nom)*/
        public string $slug = "",

        /** @var string La vidéo source dont il est extrait*/
        public string $source = "",

        /** @var string Timecode début*/
        public string $timecode_start = "",

        /** @var string Timecode fin*/
        public string $timecode_end = "",
    ) {
    }
}
