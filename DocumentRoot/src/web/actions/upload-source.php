<?php

/**
 * Traite le formulaire de login
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/FormInput.php';
require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../../models/Credentials.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-accounts.php';

/**
 * Authentifie l'utilisateur
 * @global array $_POST
 * @global array $_SESSION
 */
function upload_source()
{

    //Authentifier l'utilisateur
    if (!current_user_can('add_source')) {
        api_respond_with_error();
    }

    //Check le token
    //Valider le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'add_source'))) {
        api_respond_with_error();
    }

    //Validation des inputs du formulaire
    $input_validations = check_upload_source_form();
    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {
        header('Content-Type: application/json; charset=utf-8');
        $response =  json_encode(array(
            'statut' => 403,
            'errors' => array_map(function ($invalid_input) {
                return array(
                    'name' => $invalid_input->name,
                    'value' => $invalid_input->value,
                    'message' => $invalid_input->message
                );
            }, $invalid_inputs),
        ));
        echo $response;
        exit;
    }

    echo 'ok';
}


function check_upload_source_form()
{

    $form_inputs = array(

        new FormInput('source_url', filter_input(INPUT_POST, 'source_url'), function (string $source_url): InputValidation {

            //Non vide.
            if (!isset($source_url) || empty($source_url))
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");

            //Format valide.
            if ($source_url !== filter_input(INPUT_POST, 'source_url', FILTER_SANITIZE_URL)) {
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");
            }

            //Domaine autorisé.
            if (!defined('ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM'))
                throw new Exception('Allowed domains n\'est pas défini. Potentielle faille.');

            $url_parts = parse_url($source_url);

            $domain = $url_parts['host'] ?? '';

            if (empty($domain) || !in_array($domain, ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM)) {
                return new InputValidation(
                    'source_url',
                    $source_url,
                    sprintf(
                        "Renseignez une url valide de vidéo source à télécharger. Voici les domaines autorisés: %s",
                        implode(", ", ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM)
                    )
                );
            }

            //Contrainte sur la chaine youtube (juste celle de canardPC)
            return new InputValidation('source_url', $source_url, '', InputStatus::Valid);
        }),

        new FormInput('series', filter_input(INPUT_POST, 'series'), function (string $series): InputValidation {

            if (!defined('SOURCE_SERIES'))
                throw new Exception('SOURCE_SERIES n\'est pas défini.');

            //Nom de série autorisé.
            if (!in_array($series, SOURCE_SERIES)) {
                return new InputValidation(
                    'series',
                    $series,
                    "Renseignez un nom de série valide"
                );
            }

            return new InputValidation('series', $series, '', InputStatus::Valid);
        }),

        new FormInput('name', filter_input(INPUT_POST, 'name'), function (string $name): InputValidation {
            //Non vide.
            if (!isset($name)) {
                return new InputValidation('name', $name, "Renseignez un identifiant.");
            }

            //Seulement alphanumerique ou '#', sans espace, entre 1 et 25 caractères
            //Ne marche pas !!
            if (!(preg_match('/[a-z0-9]{1,25}/', $name))) {
                return new InputValidation('name', $name, "Renseignez un identifiant valide. Seuls les caratères de a à z et de 0 à 9 sont autorisés.");
            }

            return new InputValidation('name', strval($name), '', InputStatus::Valid);
        }),

        new FormInput('nb_files', count($_FILES), function (int $nb_files): InputValidation {
            if (1 == !$nb_files)
                return new InputValidation('name', $nb_files, "Uploadez un et un seul fichier.");
            return new InputValidation('nb_files', strval($nb_files), '', InputStatus::Valid);
        }),

        new FormInput('file', $_FILES, function (array $files): InputValidation {

            $uploaded_file = $files[0];
            $allowed_mim_types = array('mp4/video');

            if (!in_array($uploaded_file['type'], $allowed_mim_types))
                return new InputValidation('file', $uploaded_file['type'], "Seuls les fichiers au format " . implode(',', $allowed_mim_types));

            return new InputValidation('file', 'file', '', InputStatus::Valid);
        }),

        new FormInput('max_size', $_FILES, function (array $files): InputValidation {

            $uploaded_file = $files[0];

            if (intval($uploaded_file['size']) / 1000 > MAX_UPLOAD_SIZE_IN_MB)
                return new InputValidation('max_size', $uploaded_file['size'], 'Le fichier dépasse la limite d\'upload autorisée');

            return new InputValidation('max_size', $uploaded_file['size'], '', InputStatus::Valid);
        }),
    );

    return validate_posted_form($form_inputs);
}
