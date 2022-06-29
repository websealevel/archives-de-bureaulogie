<?php

/**
 * Gere requete AJAX pour gérer les marqueurs d'un utilisateur sur une vidéo source
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Vendor
 */
require_once __DIR__ . '/../../../vendor/autoload.php';


/**
 * Functions
 */
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../database/queries-markers.php';


/**
 * Traite la requête AJAX de gestion des marqueurs.
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_markers()
{
    load_env();

    //Authentifier l'utilisateur
    if (!current_user_can('submit_clip')) {
        api_respond_with_error();
    }

    $data = filter_input_array(INPUT_POST, array(
        'action' => FILTER_SANITIZE_ENCODED,
        'source_name' => FILTER_SANITIZE_ENCODED,
        'position_in_sec' => FILTER_SANITIZE_ENCODED
    ));

    $clean = array();

    //Check action
    if (!isset($data['action']) && !in_array($data['action'], array(
        'add', 'remove'
    ))) {
        api_respond_with_error(array(
            new InputValidation('', '', "Action invalide")
        ));
    }

    $clean['action'] = $data['action'];

    //Check source_name
    if (!isset($data['source_name'])) {
        api_respond_with_error();
    }
    //Remarque: on recupere le source_name comme 'sources/{nom de la source}'. Le slash est encodé
    $pattern = '%2F'; //slash
    $pos = strpos($data['source_name'], '%2F');
    $source_name = substr($data['source_name'], $pos + strlen($pattern));

    if (!(source_has_valid_filename_format($source_name) && source_exists($source_name))) {
        api_respond_with_error(array(
            new InputValidation('', '', "Source invalide")
        ));
    }

    $clean['source_name'] = $source_name;

    //Check position_in_sec
    if (!isset($data['position_in_sec'])) {
        api_respond_with_error();
    }

    if (!is_numeric($data['position_in_sec'])) {
        api_respond_with_error(array(
            new InputValidation('', '', "Position (en secondes) invalide")
        ));
    }

    $clean['position_in_sec'] = $data['position_in_sec'];

    //tmp
    $account_id = 1;

    switch ($clean['action']) {
        case 'add':
            try {
                $id = sql_insert_marker($clean['source_name'], $account_id, $clean['position_in_sec']);
            } catch (PDOException $e) {
                error_log($e);
                api_respond_with_error(array(
                    new InputValidation('', '', "Impossible de sauvegarder le marqueur")
                ));
            }

            break;
        case 'delete':
            break;
        default:
            api_respond_with_error();
    };

    echo 'Sauvegarde du marqueur...';

    exit;
}
