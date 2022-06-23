<?php

/**
 * Gere requete AJAX pour creer un clip
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


/**
 * Traite la requête AJAX/formulaire de génération d'un extrait
 * @global array $_POST
 * @global array $_ENV
 * @return void
 */
function api_clip_source()
{
    load_env();

    //UX
    usleep(500000);

    //Authentifier l'utilisateur
    if (!current_user_can('submit_clip')) {
        api_respond_with_error();
    }
    //Valider le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'submit_clip'))) {
        api_respond_with_error();
    }

    //Valider le formulaire
    $inputs = check_submit_clip_form();
    $invalid_inputs = filter_invalid_inputs($inputs);

    if (!empty($invalid_inputs)) {
        //Envoyez le tableau d'erreurs
        api_respond_with_error($invalid_inputs);
    }

    //=> Utilisateur authentifié, token valide, formulaire validé.

    $timecode_start = $inputs['timecode_start']->value;
    $timecode_end = $inputs['timecode_end']->value;
    //Fix: on extrait le nom du fichier de l'url
    $source_name =  substr_replace($inputs['source_name']->value, '', 0, strlen(DIR_SOURCES) + 1);

    //Valider les timecodes
    try {
        $result = are_timecodes_valid_core($timecode_start, $timecode_end, $source_name);
        if (false === $result) {
            api_respond_with_error(array(
                new InputValidation('', '', "Les timecodes ne sont pas valides. Veuillez les corriger s'il vous plaît.")
            ));
        }
    } catch (Exception $e) {
        api_respond_with_error(array(
            new InputValidation('', '', $e->getMessage())
        ));
    }

    //Valider que le clip n'existe pas déjà pour cette source.
    if (is_clip_already_declared($source_name, $timecode_start, $timecode_end)) {
        api_respond_with_error(array(
            new InputValidation('', '', "Cet extrait existe déjà dans les archives. Veuillez en proposer un autre")
        ));
    }

    //=> Création du clip.

    //Pour les clips : on les déclare puis on les génere (c'est comme ça qu'est pensé le core. Il parse le fichier source et génère les clips qui n'existent pas encore.)

    //Déclarer le clip dans le fichier source (i.e "l'enregistrer dans les archives")
    try {
        $extrait = declare_clip(
            $source_name,
            $timecode_start,
            $timecode_end,
            $inputs['title']->value,
            $inputs['description']->value,
            current_user_pseudo(),
            'le ' . date('d-m-Y') . ' à ' . date('H:m:s')
        );

        if (false === $extrait) {
            throw new Exception("Une errer est survenue lors de l'enregistrement de l'extrait. Veuillez rééssayer.");
        }
    } catch (Exception $e) {
        api_respond_with_error(array(
            new InputValidation('', '', $e->getMessage())
        ));
    }

    /**
     * Si ici ça échoue il est possible que des extraits déclarés ne soient pas enregistrés sous forme de fichier. Dans ce cas, donner la possibilité aux admins de mettre à jour les archives (parser le fichier source pour générer extraits manquants, supprimer extraits non enregistrés etc.) peut être intéressant.
     */

    $file = clip_source($extrait);
    write_log($file);
    exit;

    //Renvoyer un markup html contenant le nouveau clip à ajouter à la liste des extraits présents sur la source.
    $label = 'foobar';
    $src = 'foobar';

    $html = html_details(
        $label,
        html_video_markup($src, 500) . html_download_link($src)
    );

    header('Content-Type: application/json; charset=utf-8');
    $response = json_encode(array(
        'statut' => 200,
        'extrait' => $html
    ));
    echo $response;
    exit;
}

/**
 * Retourne une erreur de l'api au client, avec un message et un status code
 * @param InputValidation[] $input_errors Les erreurs sur chaque champ
 * @param string $code Le code HTTP de la requête
 * @return void
 */
function api_respond_with_error(array $invalid_inputs = array(
    new InputValidation('', '', 'Les données transmises ne sont pas valides')
), string $code = '403'): void
{
    header('Content-Type: application/json; charset=utf-8');
    $response =  json_encode(array(
        'statut' => $code,
        'errors' => array_map(function ($invalid_input) {
            return array(
                'name' => $invalid_input->name,
                'value' => $invalid_input->value,
                'message' => $invalid_input->message
            );
        }, $invalid_inputs)
    ));
    echo $response;
    exit;
}

/**
 * Retourne les inputs validés (ou non) du formulaire de soumission d'extrait
 * @return InputValidation[] 
 * @return InputValidation[] Un tableau de champs validés
 * @global $_POST
 */
function check_submit_clip_form()
{
    $form_inputs = array(

        new FormInput(
            'timecode_start',
            filter_input(INPUT_POST, 'timecode_start'),
            function (string $timecode_start): InputValidation {

                //Non vide.
                if (!isset($timecode_start) || empty($timecode_start))
                    return new InputValidation('timecode_start', $timecode_start, "Renseignez un timecode de début non vide");

                //Respecte un format regex
                if (!preg_match("=^[0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{3}$=", $timecode_start)) {
                    return new InputValidation('timecode_start', $timecode_start, "Renseignez un timecode de début au format valide");
                }

                return new InputValidation('timecode_start', $timecode_start, '', InputStatus::Valid);
            }
        ),
        new FormInput(
            'timecode_end',
            filter_input(INPUT_POST, 'timecode_end'),
            function (string $timecode_end): InputValidation {

                //Non vide.
                if (!isset($timecode_end) || empty($timecode_end))
                    return new InputValidation('timecode_end', $timecode_end, "Renseignez un timecode de fin non vide");

                //Respecte un format regex
                if (!preg_match("=^[0-9]{2}:[0-9]{2}:[0-9]{2}\.[0-9]{3}$=", $timecode_end)) {
                    return new InputValidation('timecode_start', $timecode_end, "Renseignez un timecode de fin au format valide");
                }

                return new InputValidation('timecode_end', $timecode_end, '', InputStatus::Valid);
            }
        ),
        new FormInput(
            'title',
            filter_input(INPUT_POST, 'title'),
            function (string $title): InputValidation {

                //Ne doit pas être non vide et limite de taille
                if (!isset($title) || mb_strlen($title) > TWEET_NB_MAX_CHARACTERS)
                    return new InputValidation('title', $title, sprintf("Veuillez renseigner un titre. Celui-ci ne doit pas dépasser %s caractères", TWEET_NB_MAX_CHARACTERS));

                return new InputValidation('title', $title, "", InputStatus::Valid);
            }
        ),
        new FormInput(
            'description',
            filter_input(INPUT_POST, 'description'),
            function (string $description): InputValidation {

                //Ne doit pas être non vide et limite de taille
                if (!isset($description) || mb_strlen($description) > TWEET_NB_MAX_CHARACTERS)
                    return new InputValidation('description', $description, sprintf("La description ne doit pas dépasser %s caractères", TWEET_NB_MAX_CHARACTERS));

                return new InputValidation('description', $description, '', InputStatus::Valid);
            }
        ),
        new FormInput(
            'source_name',
            filter_input(INPUT_POST, 'source_name'),
            function (string $source_name): InputValidation {

                //Ne doit pas être non vide et limite de taille
                if (!isset($source_name) || empty($source_name))
                    return new InputValidation('source_name', $source_name, 'Veuillez choisir une vidéo source à parti de laquelle vous souhaitez créer un extrait');

                return new InputValidation('source_name', $source_name, '', InputStatus::Valid);
            }
        )

    );

    return validate_posted_form($form_inputs);
}
