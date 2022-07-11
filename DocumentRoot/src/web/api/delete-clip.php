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

    //Verifier: user_can, token, extrait existe (ainsi que sa source), email donné correspond à l'email de l'utilisateur en session (aka l'extrait lui appartient)


    //Authentifier l'utilisateur
    if (!current_user_can('remove_clip')) {
        api_respond_with_error();
    }

    //Valider le token
    if (!($_POST['token'] && is_valid_token($_POST['token'], 'delete_clip'))) {
        api_respond_with_error();
    }

    write_log('so far so good...');
}