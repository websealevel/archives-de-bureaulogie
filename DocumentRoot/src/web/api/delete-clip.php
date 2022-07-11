<?php

/**
 * Gere requete AJAX pour supprimer un extrait (qui appartient à l'auteur)
 *
 * @link
 *
 * @package wsl 
 */


function api_delete_clip()
{
    load_env();

    //Authentifier l'utilisateur+autorization
    if (!current_user_can('remove_clip')) {
        api_respond_with_error();
    }

    //Valider le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'delete_clip'))) {
        api_respond_with_error();
    }


    //Valider le formulaire
    $inputs = check_delete_clip_form();
    $invalid_inputs = filter_invalid_inputs($inputs);

    if (!empty($invalid_inputs)) {
        //Envoyez le tableau d'erreurs
        api_respond_with_error($invalid_inputs);
    }

    $clean['clip_name'] = filter_input(INPUT_POST, 'clip_name');

    //Supprimer le fichier

    $path_parts = pathinfo($clean['clip_name']);
    $basename = $path_parts['basename'];

    $file_deleted = delete_file_clip($basename);

    if (!$file_deleted) {
        $message = sprintf("Le fichier %s n'a pas pu être supprimé mais il a été déclaré au moment de la demande de suppression", $basename);
        error_log($message);
    }

    //Supprimer la declaration
    $metadata = extract_metadata_from_clip_name($basename);

    $clip_removed_from_source = remove_clip($metadata['source_name'], $metadata['timecode_start'], $metadata['timecode_end']);

    if (!$clip_removed_from_source) {
        $message = sprintf("Le clip %s n'a pas pu être retiré du fichier source", $basename);
        error_log($message);
        api_respond_with_error(array(new InputValidation('', '', "Le clip n'a pas pu être supprimé. Une erreur est survenue.")));
    }

    api_respond_with_success(data: array(), key: 'extrait');
}


/**
 * Valide le formulaire de suppression d'extrait. L'extrait doit être déclaré (ainsi que sa source) et appartenir à l'utilisateur (email de l'extrait match l'email en session de l'utilisateur connecté)
 */
function check_delete_clip_form()
{

    //Extrait existe
    //Email correspond à celui en session
    $form_inputs = array(
        new FormInput(
            'clip_name',
            filter_input(INPUT_POST, 'clip_name'),
            function (string $clip_name): InputValidation {

                //Non vide.
                if (!isset($clip_name) || empty($clip_name))
                    return new InputValidation('clip_name', $clip_name, "Renseignez un nom d'extrait à supprimer");

                //Clip au bon format
                $path_parts = pathinfo($clip_name);
                $basename = $path_parts['basename'];

                if (!clip_has_valid_filename_format($basename)) {
                    return new InputValidation('clip_name', $clip_name, "Le nom du clip à supprimer est invalide");
                }

                //Clip déclaré
                $metadata = extract_metadata_from_clip_name($basename);
                if (!is_clip_already_declared($metadata['source_name'], $metadata['timecode_start'], $metadata['timecode_end'])) {
                    return new InputValidation('clip_name', $clip_name, "Impossible de supprimer l'extrait car il n'est pas déclaré.");
                }

                return new InputValidation('clip_name', $clip_name, '', InputStatus::Valid);
            }
        ),

        new FormInput(
            'author_email',
            filter_input(INPUT_POST, 'author_email'),
            function (string $author_email): InputValidation {

                //Non vide.
                if (!isset($author_email) || empty($author_email) || !filter_var($author_email, FILTER_VALIDATE_EMAIL))
                    return new InputValidation('author_email', $author_email, "Renseignez l'email de l'auteur du clip à supprimer");

                //Email match email de l'utilisateur en session
                if ($author_email !== current_user_email())
                    return new InputValidation('author_email', $author_email, "Ce clip ne vous appartient pas, vous ne pouvez pas le supprimer");

                return new InputValidation('clip_name', $author_email, '', InputStatus::Valid);
            }
        ),
    );

    return validate_posted_form($form_inputs);
}
