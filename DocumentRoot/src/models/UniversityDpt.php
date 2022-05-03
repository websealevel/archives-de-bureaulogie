<?php

/**
 * Class wrappan un département de recherche/enseignement de l'université
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . 'UniversityResearcher.php';


class UniversityDpt
{

    public function __construct(

        public  string $key,
        public  string $label,
        public UniversityResearcher $chairman,
    ) {
    }
}
