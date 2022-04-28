<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';

/**
 * Retourne toutes les routes de l'application sous la clef de leurs méthodes respectives
 * @return array
 */
function routes(): array
{
    return  array(
        'get' => array(
            '/' => function () {
                present_template('home');
            },
        )
    );
}
