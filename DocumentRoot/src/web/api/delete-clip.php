<?php

/**
 * Gere requete AJAX pour supprimer un extrait (qui appartient à l'auteur)
 *
 * @link
 *
 * @package wsl 
 */


function api_delete_clip(){

    write_log('delete clip request');

    //Verifier: user_can, token, extrait existe (ainsi que sa source), email donné correspond à l'email de l'utilisateur en session (aka l'extrait lui appartient)
}