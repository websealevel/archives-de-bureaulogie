<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */


/**
 * Retourne toutes les routes de l'application sous la clef de leurs méthodes respectives
 * @return array
 */
function routes(): array
{
    return  array(
        'get' => array(
            '/' => function () {
                echo 'Welcome home : )';
            },
            '/contact' => function () {
                echo "Let's keep in touch :)";
            }
        )
    );
}
