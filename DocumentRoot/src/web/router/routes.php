<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../actions/log-in.php';
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
                start_session();
                present_template('sign-up');
            },
            '/log-out' => function () {
                session_destroy();
                present_template('log-out');
            }
        ),
        'post' => array(
            '/log-in' => function () {
                log_in();
            },
            '/sign-up' => function () {
                sign_up_user();
            }
        )
    );
}
