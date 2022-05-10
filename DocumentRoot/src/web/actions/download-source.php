<?php

/**
 * Traite le formulaire d'ajout de source
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/FormInput.php';
require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-roles-capabilities.php';
require_once __DIR__ . '/../core-interface.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

/**
 * Télécharge une vidéo source depuis une url valide vers le dossier source
 * @global array $_POST
 * @global array $_SESSION
 */
function download_source()
{
    session_start();

    $form_inputs = array(

        new FormInput('source_url', $_POST['source_url'], function (string $source_url): InputValidation {

            //Non vide.
            if (empty($source_url))
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");

            //Format valide.
            if ($source_url !== filter_input(INPUT_POST, 'source_url', FILTER_SANITIZE_URL)) {
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");
            }

            //Domaine autorisé.
            if (!defined('ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM'))
                throw new Exception('Allowed domains n\'est pas défini. Potentielle faille.');

            $url_parts = parse_url($source_url);

            $domain = $url_parts['host'];

            if (!in_array($domain, ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM)) {
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

        new FormInput('series', $_POST['series'], function (string $series): InputValidation {

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

        new FormInput('slug', $_POST['slug'], function (string $slug): InputValidation {
            //Non vide.
            if (empty($slug))
                return new InputValidation('slug', $slug, "Renseignez une identifiant valide.");

            //Seulement alphanumerique(hyphen), sans espace, entre 1 et 12 caractères
            return new InputValidation('slug', $slug, '', InputStatus::Valid);
        })
    );

    //Validation des champs
    $input_validations = validate_posted_form($form_inputs);

    //Filtrer que les champs avec un champs 'errors' non vide et status invalid.
    $invalid_inputs = array_filter($input_validations, function (InputValidation $input) {
        return InputStatus::Invalid === $input->status;
    });

    //Si des validations ont échoué, on retourne à la page avec les erreurs
    if (!empty($invalid_inputs))
        redirect('/download-source', 'form_errors', $input_validations);

    //Validation du mot de passe si action sensible.
    if (is_authentification_confirmation_required('add_source')) {
        redirect('/confirm-authentification');
    }

    dd('Let s download');
    // $download_request = new DownloadRequest(
    //     $input_validations['source_url']->value,
    // );
    // download();
}
