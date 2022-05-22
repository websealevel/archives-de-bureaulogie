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
    <h3>Source</h3>
    <div class="video-source">
        <video width="600" controls id="video-source">
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>

    <div class="video-clip">
        <h3>Extrait</h3>
        <video width="600" height="240" controls id="video-clip">
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>
</div>

<main class="form-clip">
    <form action="clip-source" id="form-clip-source" name="form-clip-source">
        <label for=" title">Titre</label>
        <input type="text" name="title">
        <label for="timecode_start">Début </label>
        <input type="time" name="timecode_start" value="00:00:00.000">
        <label for="timecode_fin">Fin </label>
        <input type="time" name="timecode_start" value="00:00:00.000">
        <label for="title">Description</label>
        <textarea name="description" rows="4" cols="50"></textarea>

        <input type="submit" value="Extraire">
    </form>
</main>

<side>
    <h2>Extraits existants</h2>
    <!-- Rempli en jquery -->
    <div class="list-clips></div>
</side>


<?php present_footer(array('jquery-min', 'template-clip')); ?>