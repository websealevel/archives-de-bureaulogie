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
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../../handlers.php';
?>

<?php present_header(); ?>

<?php if (!is_current_user_logged_in()) : ?>

    <?php present_template_part('form-login'); ?>

<?php else : ?>

    <h2>Bonjour <?php esc_html_e(current_user_pseudo()); ?> !</h2>

    <a href="/log-out">se déconnecter</a>

    <h3>Archives vidéos</h3>

    <ul>
        <?php if (current_user_can('submit_clip')) :  ?>
            <li><a href="clip">Soumettre un nouvel extrait</a></li>
        <?php endif;  ?>

        <?php if (current_user_can('add_source')) :  ?>
            <li><a href="download-source">Importer une nouvelle vidéo source </a>
                <?php esc_active_downloads_info_e(); ?></li>
        <?php endif;  ?>

    </ul>

    <h3>Archives bibliographiques</h3>

    <ul>
        <?php if (current_user_can('submit_reference')) :  ?>
            <li><a href="submit_ref">Soumettre une référence bibliographique</a></li>
        <?php endif;  ?>
    </ul>

<?php endif; ?>

<?php present_footer(); ?>