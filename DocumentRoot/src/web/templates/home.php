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


    <div class="annonce">Les archives de bureaulogie sont en cours de construction... Elles ouvriront bientôt leurs portes au public. Merci de votre compréhension.</div>
<?php else : ?>

    <p>Salut <?php esc_html_e(current_user_pseudo()); ?> !</p>

    <a href="/log-out">se déconnecter</a>

    <main>


        <h2>Les archives</h2>

        <section>
            <h3>Extraits</h3>
            <?php if (current_user_can('submit_clip')) :  ?>
                <a href="clip">Consulter, éditer les extraits vidéos</a>
            <?php endif;  ?>
        </section>

        <section>
            <h3>Vidéos sources</h3>
            <?php if (current_user_can('add_source')) :  ?>
                <a href="download-source">Consulter, importer les vidéos sources</a>
                <?php esc_active_downloads_info_e(); ?>
            <?php endif;  ?>
        </section>


        <section>
            <h3>Références bibliographiques</h3>
            <?php if (current_user_can('submit_reference')) :  ?>
                <a href="submit_ref">Consulter, proposer des références bibliographiques</a>
            <?php endif;  ?>
        </section>


    </main>

<?php endif; ?>

<?php present_footer(); ?>