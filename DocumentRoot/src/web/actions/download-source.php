<?php

/**
 * Traite le formulaire d'ajout de source
 * @link
 *
 * @package wsl 
 */

/**
 * Models
 */
require_once __DIR__ . '/../../models/FormInput.php';
require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../../models/DonwloadRequest.php';

/**
 * Utils
 */
require_once __DIR__ . '/../utils.php';

/**
 * Database
 */
require_once __DIR__ . '/../database/repository-roles-capabilities.php';

/**
 * Interface avec la partie core
 */
require_once __DIR__ . '/../core-interface.php';

/**
 * Vérifie la validité des champs du formulaire de demande de téléchargement de source. Retourne un tableau d'InputValidation correspondant à chaque champ avec son status valide ou non
 * @return InputValidation[] 
 * @throws Exception - Si la série des sources valides n'est pas définie
 */
function check_download_request_form(): array
{

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

        new FormInput('name', $_POST['name'], function (string $name): InputValidation {
            //Non vide.
            if (empty($name)) {
                return new InputValidation('name', $name, "Renseignez un identifiant.");
            }

            //Seulement alphanumerique ou '#', sans espace, entre 1 et 25 caractères
            //Ne marche pas.
            if (!(preg_match('/[a-z0-9]{1,25}/', $name))) {
                return new InputValidation('name', $name, "Renseignez un identifiant valide. Seuls les caratères de a à z et de 0 à 9 sont autorisés.");
            }

            //N'existe pas déjà en base.
            if (is_source_already_declared($_POST['series'], $name, $_POST['source_url'])) {
                return new InputValidation('name', $name, "Cette source a déjà été déclarée. Une autre source avec la même url a été trouvée.");
            }

            return new InputValidation('name', strval($name), '', InputStatus::Valid);
        })
    );


    //Validation des champs
    $input_validations = validate_posted_form($form_inputs);

    return $input_validations;
}

/**
 * Filtre les champs de formulaires invalides
 * @param InputValidation[] Les champs testés
 * @return InputValidation[] Les champs invalides
 */
function filter_invalid_inputs(array $input_validations)
{
    return array_filter($input_validations, function (InputValidation $input) {
        return InputStatus::Invalid === $input->status;
    });
}
