<?php

/**
 * Une entrée bibliographique
 *
 * @link
 *
 * @package wsl 
 */


class BibliographyEntry
{
    public function __construct(

        public string $title,
        public array $authors = array(),
        public string $editor,
        public int $nb_pages,
        public string $journal,
        public string $page,
        public string $category,
        public string $publishing_year
    ) {
    }
}
