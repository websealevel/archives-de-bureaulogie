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
        'timecode_start_in_sec' => FILTER_SANITIZE_ENCODED,
        'timecode_end_in_sec' => FILTER_SANITIZE_ENCODED,
        'title' => FILTER_SANITIZE_ENCODED,
        'marker_id' => FILTER_SANITIZE_NUMBER_INT
    ));

    $clean = api_markers_validate_input($data);

    if (false === $clean) {
        api_respond_with_error(array(
            new InputValidation('', '', "Les données transmises ne sont pas correctes.")
        ));
    }

    $account_id = current_user_id();

    if ('' === $account_id) {
        api_respond_with_error(array(
            new InputValidation('', '', "Les données transmises ne sont pas correctes.")
        ));
    }

    //Remplacer ça par un match.
    switch ($clean['action']) {

        case 'add':

            try {
                $id = sql_insert_marker($clean['source_name'], $account_id, floatval($clean['timecode_start_in_sec']), floatval($clean['timecode_end_in_sec']), $clean['title']);
                api_respond_with_success($id);
            } catch (PDOException $e) {
                error_log($e);
                api_respond_with_error(array(
                    new InputValidation('', '', "Impossible de sauvegarder le marqueur")
                ));
            }
            break;

        case 'remove':

            try {
                $row_affected = sql_delete_marker($account_id, $clean['marker_id']);
                api_respond_with_success($row_affected);
            } catch (PDOException $e) {
                error_log($e);
                api_respond_with_error(array(
                    new InputValidation('', '', "Impossible de supprimer le marqueur")
                ));
            }
            break;

        case 'fetch':

            try {
                $markers = sql_find_markers_on_source_by_account_id($clean['source_name'], $account_id);
                api_respond_with_success($markers, 'markers');
            } catch (PDOException $e) {
                error_log($e);
                api_respond_with_error(array(
                    new InputValidation('', '', "Impossible de récupérer les marqueurs")
                ));
            }
            break;

        default:
            api_respond_with_error();
    };
}

/**
 * Retourne les données clean si toutes les données sont validées, faux sinon
 * @param array $data Les données nécessaires au traitement d'une requête sur les marqueurs
 * @return array|false
 */
function api_markers_validate_input($data): array|false
{
    $clean = array();

    //Check action
    if (!isset($data['action']) && !in_array($data['action'], array(
        'add', 'remove', 'fetch'
    ))) {
        return false;
    }

    $clean['action'] = $data['action'];


    if ('remove' === $clean['action']) {
        if (isset($data['marker_id']) && is_numeric($data['marker_id'])) {
            $clean['marker_id'] = $data['marker_id'];
        }

        return $clean;
    }

    //Check source_name
    if (!isset($data['source_name'])) {
        return false;
    }
    //Remarque: on recupere le source_name comme 'sources/{nom de la source}'. Le slash est encodé
    $pattern = '%2F'; //slash
    $pos = strpos($data['source_name'], '%2F');
    $source_name = substr($data['source_name'], $pos + strlen($pattern));

    if (!(source_has_valid_filename_format($source_name) && source_exists($source_name))) {
        return false;
    }

    $clean['source_name'] = $source_name;

    if ('fetch' === $clean['action']) {
        return $clean;
    }

    if (!isset($data['timecode_start_in_sec'])) {
        return false;
    }

    if (!is_numeric($data['timecode_start_in_sec'])) {
        return false;
    }

    $clean['timecode_start_in_sec'] = $data['timecode_start_in_sec'];

    if (!isset($data['timecode_end_in_sec'])) {
        return false;
    }

    if (!is_numeric($data['timecode_end_in_sec'])) {
        return false;
    }

    $clean['timecode_end_in_sec'] = $data['timecode_end_in_sec'];

    $clean['title'] = htmlentities($data['title']);

    return $clean;
}
