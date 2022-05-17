<?php

/**
 * Un wrap des données d'un Extrait (donné par le dtd)
 *
 * @link extraits.dtd
 * @package wsl 
 */


class Clip
{
    public function __construct(

        /** @var string Le slug (nom)*/
        public string $slug = "",

        /** @var string La vidéo source dont il est extrait*/
        public string $source = "",

        /** @var string Un titre*/
        public string $title = "",

        /** @var string Une description*/
        public string $description = "",

        /** @var string Timecode début*/
        public string $timecode_start = "",

        /** @var string Timecode fin*/
        public string $timecode_end = "",

        /** @var string Auteur*/
        public string $author = "",

        /** @var string Date de création*/
        public string $created_on = "",

    ) {
    }
}
