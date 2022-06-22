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


if (!current_user_can('submit_clip'))
    redirect('/', 'notices', array(new Notice('Vous devez être authentifié pour soumettre un clip', NoticeStatus::Error)));

?>

<?php present_header(); ?>

<h2>Créer un extrait</h2>

<form action="">
    <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
</form>
<div class="videos">
    <h3>Source</h3>
    <div class="video-source">
        <video id="video-source" width="600" controls>
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>
    <h3>Extrait</h3>

    <div class="video-clip">
        <video id="video-clip" width="600" controls>
            <source src="" type="video/mp4">
            Votre navigateur ne supporte pas le tag video HTML5 :(
        </video>
    </div>
</div>

<main class="form-clip">
    <form action="clip-source" id="form-clip-source" name="form-clip-source">

        <label for=" title">Titre</label>
        <input type="text" name="title" size="100">

        <label for="timecode_start">Début </label>
        <input type="time" name="timecode_start" id="timecode_start" value="00:00:00.000">

        <label for="timecode_fin">Fin </label>
        <input type="time" name="timecode_end" id="timecode_end" value="00:00:00.000">

        <label for="title">Description</label>
        <textarea name="description" rows="4" cols="50"></textarea>

        <input name="btn_clip_start" id="btn_clip_start" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" value="Démarrer l'extrait ici">

        <input name="bt_clip_end" id="btn_clip_end" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" value="Finir l'extrait ici">

        <input type="button" id="btn_preview" value="Prévisualiser">

        <fieldset id="clip-options">
            <legend>Options</legend>
            <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
            <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
        </fieldset>

        <input type="submit" value="Extraire">
    </form>
</main>

<side>
    <h2>Extraits existants</h2>
    <div id="list-clips-on-current-source"></div>
</side>


<?php present_footer(array('jquery-min', 'template-clip')); ?>