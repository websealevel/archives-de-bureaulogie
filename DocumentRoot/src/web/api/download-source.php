<?php

/**
 * Gere requete AJAX pour télécharger une vidéo source
 * Ouvre un websocket
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../actions/download-source.php';
require_once __DIR__ . '/../../models/DonwloadRequest.php';
require_once __DIR__ . '/../../core/download.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-downloads.php';

autoload();

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

function api_download_source()
{
    session_id($_POST['PHPSESSID']);
    session_start();

    if (!current_user_can('add_source')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 500,
            'errors' => array(new Notice(
                "Vous n'avez pas l'autorisation.",
                NoticeStatus::Error
            ))
        ));
        exit;
    }

    //Check le form
    $input_validations = check_download_request_form();

    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {
        print_r(array(
            'statut' => 400,
            'errors' => $invalid_inputs,
        ));
        exit;
    }

    $download_request = new DownloadRequest(
        $input_validations['source_url']->value,
        $input_validations['series']->value,
        $input_validations['name']->value,
    );

    //Lancement du téléchargement de la source
    check_download_request($download_request);

    $authentificated_user_id = from_session('account_id');

    //On enregistre en base une demande associée à la session
    $response = create_download($download_request, $authentificated_user_id);

    //En cas d'erreur sur le formulaire ou d'accès à la base.
    if ($response instanceof Notice) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 403,
            'errors' => array($response),
        ));
        //En cas de formulaire valide.
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 200,
            'errors' => array(),
        ));
    }
    exit;
}
