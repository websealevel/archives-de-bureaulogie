<?php

/**
 * Page gérant le téléchargement de nouvelles vidéos sources
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../core-interface.php';

session_start();

if (!current_user_can('add_source'))
    redirect('/');
?>

<?php present_header(); ?>

<h2>Ajouter une nouvelle vidéo source à la bibliothèque</h2>

<div>
    <a href="/">Retour</a>
</div>

<?php esc_html_list_sources_e(); ?>

<h3>Ajouter</h3>
<?php esc_html_form_error_msg_e('source_url', 'form_errors'); ?>
<form action="download-source" method="POST">
    <label for="source_url">Renseigner l'url youtube de la vidéo</label>
    <input type="url" name="source_url">
    <input type="submit" value="Ajouter">
</form>

<div name="preview_source">
    <video width="320" height="240" controls>
        <source src="" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>
</div>


<?php present_footer(); ?>