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
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("La récupération de l'historique des téléchargements a échoué"),
                NoticeStatus::Error
            )
        ));
    }

    if (honey_pot_filled('fax')) {
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Pas de robot ici"),
                NoticeStatus::Error
            )
        ));
    }

    //Check le token
    //Valider le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'add_source'))) {
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Vous n'avez pas l'autorisation."),
                NoticeStatus::Error
            )
        ));
    }

    //Validation des inputs du formulaire
    $input_validations = check_upload_source_form();
    $invalid_inputs = filter_invalid_inputs($input_validations);

    //Retourner les erreurs sur les champs
    if (!empty($invalid_inputs)) {


        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Champs non valides"),
                NoticeStatus::Error
            )
        ));
    }


    $clean = filter_input_array(INPUT_POST, array(
        'series' => FILTER_UNSAFE_RAW,
        'name' => FILTER_UNSAFE_RAW,
        'source_url' => FILTER_SANITIZE_URL,
    ));

    //Check que la video n'est pas déja enregistrée dans le fichier source (une source avec la même url)
    if (is_source_already_declared($clean['series'], $clean['name'], $clean['source_url'])) {;
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Une source avec ce nom et/ou cette url est déjà renseignée dans les archives"),
                NoticeStatus::Error
            )
        ));
    }

    //Check que la vidéo à télécharger n'a pas un nom déjà utilisé par une autre vidéo source
    if (source_exists(format_to_source_file_raw($clean['series'], $clean['name']))) {
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Une source avec ce nom est déjà renseignée dans les archives"),
                NoticeStatus::Error
            )
        ));
    }

    try {
        $file_name = check_uploaded_file($clean['series'], $clean['name'],);
    } catch (Exception $e) {
        error_log($e);
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Impossible d'uploader le fichier, il n'est pas valide"),
                NoticeStatus::Error
            )
        ));
    }

    //Déplacer le fichier uploader dans le dossier des sources
    if (!move_uploaded_file($_FILES['upload_file']['tmp_name'], PATH_SOURCES . '/' . $file_name)) {
        error_log($e);
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Impossible d'enregistrer le fichier dans les archives"),
                NoticeStatus::Error
            )
        ));
    }

    //Déclarer la source
    try {
        $source_added = declare_source(
            $clean['source_url'],
            $clean['series'],
            $clean['name'],
            $file_name
        );
    } catch (Exception $e) {
        error_log($e);
        redirect('/upload-source', 'notices', array(
            new Notice(
                sprintf("Impossible d'enregistrer l'archive."),
                NoticeStatus::Error
            )
        ));
    }
}


/**
 * Vérifie que le fichier uploadé est valide, retourne le nom du fichier formatté si aucune erreur
 * @param string $series
 * @param string $name
 * @param string $source_url
 * @throws Exception - Si l'upload a rencontré une erreur
 * @throws Exception - Si plusieurs fichiers ont été uploadés en même temps
 * @throws Exception - Si le format(mimtype) du fichier n'est pas autorisé
 * @throws Exception - Si la tailel de l'upload dépasse la limite
 * @global $_FILES, $_POST
 * @return string
 */
function check_uploaded_file(string $series, string $name): string
{

    if (
        !isset($_FILES['upload_file']['error']) ||
        is_array($_FILES['upload_file']['error'])
    ) {
        throw new Exception('Le fichier n\'a pas pu être uploadé');
    }

    $nb_uploaded_files = count($_FILES);

    if (!(1 === $nb_uploaded_files)) {
        throw new Exception('Uploadez un et un seul fichier');
    }

    $allowed_mim_types = array('video/mp4');
    $uploaded_file = $_FILES['upload_file'];

    if (!in_array($uploaded_file['type'], $allowed_mim_types)) {
        throw new Exception('Le format du fichier n\'est pas autorisé');
    }

    if (intval($uploaded_file['size']) / 1000 > MAX_UPLOAD_SIZE_IN_MB) {
        throw new Exception('Le fichier dépasse la limite d\'upload autorisée');
    }

    return format_to_source_file_raw($series, $name);
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
        })
    );

    return validate_posted_form($form_inputs);
}
