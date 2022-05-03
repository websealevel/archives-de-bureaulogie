<?php

/**
 * Class wrappant une spécialité d'enseignement de l'Université
 * Description:
 *
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/UniversityDpt.php';

class UniversityMajor
{
    public function __construct(

        public  string $key,
        public  string $label,
        public UniversityDpt $dpt,
    ) {
    }
}
