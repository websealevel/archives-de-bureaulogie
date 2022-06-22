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

<main class="form-clip">

    <h2>Créer un extrait</h2>

    <form action="">
        <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
    </form>


    <div id="current-time" class="current-time">
        00:00:00.000
    </div>
    <div class="videos">
        <h3>Source</h3>



        <div class="video-source">
            <video id="video-source" width="600" controls>
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas le tag video HTML5 :(
            </video>
        </div>
        <h3>Prévisualisation</h3>

        <div class="video-clip">
            <video id="video-clip" width="600" controls>
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas le tag video HTML5 :(
            </video>
        </div>
    </div>

    <div class="errors" style="color: red;"></div>

    <form action="clip-source" id="form-clip-source" name="form-clip-source">


        <fieldset id="fieldset-edition">
            <legend>Édition</legend>

            <div id="edition-actions">
                <input name=" btn_clip_start" id="btn_clip_start" type="button" class="btn-edition" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" value="Démarrer l'extrait ici">

                <input name="bt_clip_end" id="btn_clip_end" type="button" class="btn-edition" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" value="Finir l'extrait ici">

                <input type="button" id="btn_preview" value="Prévisualiser" class="btn-edition">

            </div>

            <div id="edition-data">

                <div class="label-input">
                    <label for="timecode_start">Début </label>
                    <input type="text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000">
                </div>

                <div class="label-input">
                    <label for="timecode_end">Fin </label>
                    <input type="text" name="timecode_end" id="timecode_end" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000">
                </div>

                <div class="label-input">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" size="100">
                </div>

                <div class="label-input">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" cols="50"></textarea>
                </div>


            </div>
        </fieldset>



        <fieldset id="clip-options">
            <legend>Options</legend>
            <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
            <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
        </fieldset>

        <input type="submit" value="Cut !" class="btn-edition">
    </form>
</main>

<side>
    <h2>Extraits existants</h2>
    <div id="list-clips-on-current-source"></div>
</side>


<?php present_footer(array('jquery-min', 'template-clip')); ?>