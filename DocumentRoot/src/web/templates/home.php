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

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../../handlers.php';

?>

<?php present_header(); ?>

<?php if (!is_current_user_logged_in()) : ?>

    <?php present_template_part('form-login'); ?>

<?php else : ?>

    <p>Salut <?php esc_html_e(current_user_pseudo()); ?> !</p>

    <a href="/log-out">se déconnecter</a>

    <h2>archives vidéos</h2>

    <ul>
        <?php if (current_user_can('submit_clip')) :  ?>
            <li><a href="clip">soumettre un nouvel extrait</a></li>
        <?php endif;  ?>

        <?php if (current_user_can('add_source')) :  ?>
            <li><a href="download-source">importer une nouvelle vidéo source</a>
                <?php esc_active_downloads_info_e(); ?></li>
        <?php endif;  ?>

    </ul>

    <h2>archives bibliographiques</h2>

    <ul>
        <?php if (current_user_can('submit_reference')) :  ?>
            <li><a href="submit_ref">soumettre une référence bibliographique</a></li>
        <?php endif;  ?>
    </ul>

<?php endif; ?>

<?php present_footer(); ?>