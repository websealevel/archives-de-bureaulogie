<?php

/**
 * Interface pour éditer les extraits (CRUD)
 *
 * @link
 *
 * @package wsl 
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../api/token.php';


if (!current_user_can('submit_clip'))
    redirect('/', 'notices', array(new Notice('Vous devez être authentifié pour soumettre un clip', NoticeStatus::Error)));


/**
 * Délivre un jeton pour consommer l'api au compte utilisateur
 * Fait office de nonce (previent CSRF, un jeton pour demander un téléchargement)
 */
$account = from_session('account_id');
$token_submit_clip = register_api_token($account, 'submit_clip');
$token_delete_clip = register_api_token($account, 'delete_clip');

?>

<?php present_header(); ?>

<main class="form-clip">

    <small>Conseil : pour masquer les vidéos recommandées lorsque la vidéo est mise en pause, installer l'extension <a href="https://addons.mozilla.org/en-US/firefox/addon/ublock-origin/">uBlock Origin</a>. Aller dans les <em>Settings</em> de l'extension, puis <em>My filters</em> et ajouter la règle suivante <code>youtube.com##.ytp-pause-overlay</code>. Appliquer les cahngements puis rechargez la page.</small>

    <h2>Soumettre un extrait</h2>

    <form action="clip-source">
        <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
    </form>

    <?php require_once 'parts/clip-controls.php' ?>

    <hr>

    <div class="cols-2">


        <section>
            <?php esc_html_list_clips_of_source_e(show_data: array('details'), name_attr: 'list-clips-on-current-source'); ?>
        </section>

    </div>




</main>
<script src="https://cdn.plyr.io/3.7.2/plyr.js"></script>
<?php present_footer(array('jquery-min', 'jquery-ui.min', 'template-clip')); ?>