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
$token = register_api_token($account, 'submit_clip');

?>

<?php present_header(); ?>

<main class="form-clip">

    <h2>Soumettre un extrait</h2>

    <form action="clip-source">
        <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
    </form>

    <?php require_once 'parts/clip-controls.php' ?>


</main>

<nav class="two-col-side">
    <section>
        <?php esc_html_list_clips_of_source_e(show_data: array('details'), name_attr: 'list-clips-on-current-source'); ?>
    </section>

    <section>
        <h2>Vos brouillons</h2>
        <ul id="list-markers"></ul>
    </section>
</nav>


<?php present_footer(array('jquery-min', 'template-clip')); ?>