<?php

/**
 * Gere requete AJAX pour télécharger une vidéo source
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../actions/download-source.php';

function api_download_source()
{
    session_id($_POST['PHPSESSID']);
    session_start();

    if (!current_user_can('add_source')) {
        echo 'Autorisation refusée';
        exit;
    }

    //Check le form
    $input_validations = check_download_request_form();

    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {
        print_r($invalid_inputs);
        exit;
    }

    //Lancement du téléchargement de la source
    //Mettre le téléchargement dans un process
    //Sauver le PID en base pour le tracker
    //Retourner le PID

    echo json_encode(array(
        'message' => 'Bonjour ' . $_SESSION['pseudo']
    ));
}
