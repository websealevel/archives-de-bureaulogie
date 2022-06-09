<?php

/**
 * Gere requete AJAX pour valider le formulaire de téléchargement d'une vidéo source et enregistrer une demande de téléchargement
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
 * Models
 */
require_once __DIR__ . '/../../models/DonwloadRequest.php';
require_once __DIR__ . '/../../models/enumDownloadState.php';

/**
 * Functions
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../core-interface.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../log.php';
require_once __DIR__ . '/../database/repository-downloads.php';

use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

use CrowdStar\BackgroundProcessing\BackgroundProcessing;

/**
 * Traite la requête AJAX/formulaire de téléchargement de vidéo source. Lance le téléchargement si tout est ok, retourne une erreur sinon
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_download_source()
{

    //Utilisateur authentifié et capacité 'add_source' vérifiée

    //Check le token

    //Check le form
    $input_validations = check_download_request_form();

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

    $download_request = new DownloadRequest(
        $input_validations['source_url']->value,
        $input_validations['series']->value,
        $input_validations['name']->value,
    );

    check_download_request($download_request);

    $authentificated_user_id = 1; //from_session('account_id');

    $filename = format_to_source_file($download_request);

    $response = create_download($download_request, $authentificated_user_id);

    //En cas d'erreur d'accès à la base.
    if ($response instanceof Notice) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(array(
            'statut' => 403,
            'errors' => array($response),
        ));
        exit;
    }

    $download_id = $response;

    $yt = new YoutubeDl();
    $yt->setBinPath(from_env('PATH_BIN_YOUTUBEDL'));
    $yt->setPythonPath(from_env('PATH_PYTHON'));

    //Lancer le téléchargement et écrire la progression en base.
    $db = connect_to_db();

    BackgroundProcessing::add(
        function () use ($yt, $download_id, $authentificated_user_id, $download_request, $filename, $db) {

            try {

                //Show progress
                $yt->onProgress(static function (?string $process_target, ?string $percentage, ?string $size, ?string $speed, string $eta, ?string $total_time) use ($download_id, $db): void {
                    sql_update_download($db, $download_id, $process_target, $percentage, $size, $speed, $total_time);
                });

                //Mettre l'état du download à actif
                download_change_state($download_id, DownloadState::Downloading);

                error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Downloading);

                $collection = $yt->download(
                    Options::create()
                        ->downloadPath(PATH_SOURCES)
                        ->url($download_request->url)
                        ->format(youtube_dl_download_format())
                        ->output($filename)
                );


                foreach ($collection->getVideos() as $video) {

                    if ($video->getError() !== null) {

                        //Mettre le state du dl à failed
                        download_change_state($download_id, DownloadState::Failed);

                        error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Failed);

                        throw new Exception("Error downloading video: {$video->getError()}");
                    } else {
                        //Mettre le state du dl a downloaded
                        download_change_state($download_id, DownloadState::Downloaded);

                        //Log un message propre sur le download terminé.
                        error_log_download($authentificated_user_id, $download_request->url, $filename, DownloadState::Downloaded);

                        //Mettre à jour le fichier source.

                        //Générer le label à partir de series+slug
                        $file = $video->getFile();
                    }
                }
            } catch (Exception $e) {
                error_log($e);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(array(
                    'statut' => 500,
                    'errors' => array(new Notice('Une erreur est survenue, veuillez réessayer', NoticeStatus::Error)),
                ));
            }
        }
    );

    //Lancement du téléchargement de la source en tâche de fond
    BackgroundProcessing::run();

    exit;
}





/**
 * Vérifie la validité des champs du formulaire de demande de téléchargement de source. Retourne un tableau d'InputValidation correspondant à chaque champ avec son status valide ou non
 * @return InputValidation[] 
 * @throws Exception - Si la série des sources valides n'est pas définie
 */
function check_download_request_form(): array
{

    $form_inputs = array(

        new FormInput('source_url', filter_input(INPUT_POST, 'source_url'), function (string $source_url): InputValidation {

            //Non vide.
            if (!isset($source_url))
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
            //Ne marche pas.
            if (!(preg_match('/[a-z0-9]{1,25}/', $name))) {
                return new InputValidation('name', $name, "Renseignez un identifiant valide. Seuls les caratères de a à z et de 0 à 9 sont autorisés.");
            }

            //N'existe pas déjà en base.
            if (is_source_already_declared(filter_input(INPUT_POST, 'series'), $name, filter_input(INPUT_POST, 'source_url'))) {
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
