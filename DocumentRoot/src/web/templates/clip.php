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
require_once __DIR__ . '/../core-interface.php';

session_start();

if (!current_user_can('submit_clip'))
    redirect('/', 'notices', array(new Notice('Vous devez être authentifié pour soumettre un clip', NoticeStatus::Error)));

?>

<?php present_header(); ?>

<h2>Créer un extrait</h2>

<a href="/">Retour</a>

<form action="">
    <?php esc_sources_to_html_select_e(); ?>
</form>

<div name="videos">
    <div class="video-source">
        <video width="320" height="240" controls id="video-source">
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>

    <div class="video-clip">
        <video width="320" height="240" controls id="video-clip">
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>

    <form action="">
        <label for="title">Titre</label>
        <input type="text" name="title">
        <label for="title">Description</label>
        <textarea name="description" rows="4" cols="50">
</textarea>
        <label for="timecode_start">Début (HH:mm:ss:lll)</label>
        <input type="time" name="timecode_start" value="00:00:00.000">
        <label for="timecode_fin">Fin (HH:mm:ss:lll)</label>
        <input type="time" name="timecode_start" value="00:00:00.000">
        <input type="submit" value="Extraire">
    </form>
</div>



<?php present_footer(array('jquery-min', 'template-clip')); ?>