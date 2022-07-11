<?php

/**
 * Gere requete AJAX pour supprimer un extrait (qui appartient à l'auteur)
 *
 * @link
 *
 * @package wsl 
 */


function api_delete_clip(){


    load_env();

    write_log($_POST);

    //Verifier: login, user_can, token, extrait existe (ainsi que sa source), email donné correspond à l'email de l'utilisateur en session (aka l'extrait lui appartient)


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



    write_log('so far so good...');

}


/**
 * Valide le formulaire de suppression d'extrait. L'extrait doit être déclaré (ainsi que sa source) et appartenir à l'utilisateur (email de l'extrait match l'email en session de l'utilisateur connecté)
 */
function check_delete_clip_form(){

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
                if(!clip_has_valid_filename_format($clip_name)){
                    return new InputValidation('clip_name', $clip_name, "Le nom du clip à supprimer est invalide");
                }
                
                $metadata = extract_metadata_from_clip_name($clip_name);

               //Clip déclaré
                if(!is_clip_already_declared($metadata['source'],$metadata['timecode_start'], $metadata['timecode_end'])){
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
                if (!isset($author_email) || empty($author_email))
                    return new InputValidation('author_email', $author_email, "Renseignez l'email de l'auteur du clip à supprimer");


                //Email match email de l'utilisateur en session

                return new InputValidation('clip_name', $author_email, '', InputStatus::Valid);
            }
        ),
    );

    return validate_posted_form($form_inputs);

}