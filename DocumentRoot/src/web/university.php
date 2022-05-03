<?php

/**
 * Toutes les fonctions manipulant les données de l'Université (programmes, enseignant chercheurs etc...)
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../models/UniversityMajor.php';


/**
 * Retourne la liste des Majors (Spécialités) proposées par l'université
 * @return UniversityMajor[]
 */
function university_majors(): array
{

    $majors = array(
        new UniversityMajor(
            'cable_managment',
            'Cable managment',
            new UniversityDpt(
                'ingenierie',
                'Ingénierie'
            )
        )
    );


    return $majors;
}

/**
 * Ecrit sur la sortie standard un select permettant de choisir sa Major
 * @return void
 */
function esc_html_select_majors_e(array $majors)
{
}
