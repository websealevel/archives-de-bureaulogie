<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../actions/authentificate.php';
require_once __DIR__ . '/../actions/sign-up.php';

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
            '/sign-up' => function () {
                present_template_part('form-sign-up');
            }
        ),
        'post' => array(
            '/authentificate' => function () {
                authentificate_user();
            }
        ),
        'post' => array(
            '/sign-up' => function () {
                sign_up_user();
            }
        )
    );
}
