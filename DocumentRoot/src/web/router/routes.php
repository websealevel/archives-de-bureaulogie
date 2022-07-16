<?php

/**
 * Toutes les routes de l'application
 *
 * @package wsl 
 */

/**
 * Fonctions
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';

/**
 * Server-side
 */
require_once __DIR__ . '/../actions/log-in.php';
require_once __DIR__ . '/../actions/log-out.php';
require_once __DIR__ . '/../actions/sign-up.php';
require_once __DIR__ . '/../actions/upload-source.php';
require_once __DIR__ . '/../actions/confirm-authentification.php';



/**
 * API
 */
require_once __DIR__ . '/../api/download-source.php';
require_once __DIR__ . '/../api/clip-source.php';
require_once __DIR__ . '/../api/list-clips.php';
require_once __DIR__ . '/../api/delete-clip.php';
require_once __DIR__ . '/../api/markers.php';

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
                present_template('home');
                //Remarque: désactivé tant que pb pas résolu sur vidéos 1080p avec yt-dl
                // present_template('download-source');
            },
            '/upload-source' => function () {
                present_template('upload-source');
            },
            '/submit_ref' => function () {
                present_template('submit-ref');
            },
            '/confirm-authentification' => function () {
                present_template('confirm-authentification');
            },
            '/charte' => function () {
                present_template('charte');
            },
            '/contact' => function () {
                present_template('contact');
            },
            '/nous-soutenir' => function () {
                present_template('nous-soutenir');
            },
            '/faq' => function () {
                present_template('faq');
            },
            '/confidentiality-policy' => function () {
                present_template('confidentiality-policy');
            }
        ),
        'post' => array(
            '/log-in' => function () {
                log_in();
            },
            '/confirm-authentification' => function () {
                confirm_authentification();
            },
            '/sign-up' => function () {
                sign_up_user();
            },
            '/upload-source' => function () {
                upload_source();
            },
            //API AJAX
            '/api/v1/download-source' => function () {
                api_download_source();
            },
            '/api/v1/clip-source' => function () {
                api_clip_source();
            },
            '/api/v1/list-clips' => function () {
                api_list_clips();
            },
            '/api/v1/markers' => function () {
                api_markers();
            },
            '/api/v1/delete-clip' => function () {
                api_delete_clip();
            },

        )
    );
}
