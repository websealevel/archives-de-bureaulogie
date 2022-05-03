<?php

/**
 * Home page
 * Si l'utilisateur n'est pas connecté, affiche un form de login
 * Si l'utilisateur est connecté, affiche le contenu de la page
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '../../current-user.php';
require_once __DIR__ . '/../utils.php';

start_session();

present_header();

esc_html_notices_e();

if (!is_current_user_logged_in()) {
    present_template_part('form-login');
} else {
    present_template_part('links');
}

present_footer();
