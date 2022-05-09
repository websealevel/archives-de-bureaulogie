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
            if (empty($source_url))
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");

            if ($source_url !== filter_input(INPUT_POST, 'source_url', FILTER_SANITIZE_URL)) {
                return new InputValidation('source_url', $source_url, "Renseignez une url valide de source à télécharger.");
            }

            //Check que l'url a un domaine autorisé.
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
        })
    );

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
}
