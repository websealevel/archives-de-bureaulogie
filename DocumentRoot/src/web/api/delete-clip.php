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

    );

    return validate_posted_form($form_inputs);

}