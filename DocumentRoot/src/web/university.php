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

    //A deplacer dans un fichier d'env.
    $majors = array(
        new UniversityMajor(
            'cable_managment',
            'Cable managment',
            new UniversityDpt(
                'ingenierie',
                'Ingénierie'
            )
        ),
        new UniversityMajor(
            'peripheriques_obsoletes',
            'Périphériques obsolètes',
            new UniversityDpt(
                'epistemology',
                'Épistémologie'
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
    $major_options = array_map(function ($major) {

        $label = sprintf("%s (Dpt %s)", $major->label, $major->dpt->label);

        return '<option value="' . $major->key . '">' . $label . '</option>';
    }, $majors);

    echo '<label for="major">Spécialité</label>';
    echo '<select name="major" id="">';
    foreach ($major_options as $major_option) {
        echo $major_option;
    }

    echo '</select>';
    return;
}
