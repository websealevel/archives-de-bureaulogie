<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../actions/log-in.php';
require_once __DIR__ . '/../actions/log-out.php';
require_once __DIR__ . '/../actions/sign-up.php';
require_once __DIR__ . '/../actions/authentification-confirmation.php';

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
                present_template('sign-up');
            },
            '/log-in' => function () {
                present_template('home');
            },
            '/log-out' => function () {
                log_out();
            },
            '/clip' => function () {
                present_template('clip');
            },
            '/download-source' => function () {
                present_template('download-source');
            },
            '/submit_ref' => function () {
                present_template('submit-ref');
            }
        ),
        'post' => array(
            '/log-in' => function () {
                log_in();
            },
            '/authentification-confirmation' => function () {
                confirm_authentification();
            },
            '/sign-up' => function () {
                sign_up_user();
            }
        )
    );
}
