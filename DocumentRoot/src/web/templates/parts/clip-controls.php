<?php
$color_start = '';
$color_end = '';

/**
 * Fait à la va vite pour le fun
 */
$random_phrases = array(
    "Belle journée n'est-ce pas ?",
    "Comment allez-vous aujourd'hui ?",
    "",
    "Je suis ravi de vous revoir.",
    "Je suis ravie de vous revoir.",
    "La bureaulogie n'attend pas.",
    "La bureaulogie a besoin de vous.",
    "A votre service.",
    "J'ai été mis à jour, et vous ?",
    "La bureaulogie peut sauver l'humanité. Enfin, je crois...",
    "",
);
$rando_key = array_rand($random_phrases, 1);
$phrase = $random_phrases[$rando_key];

?>

<form action="clip-source" id="form-clip-source" name="form-clip-source">

    <p>
        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>
    </p>

    <p>
        <small>Conseil : pour masquer les vidéos recommandées lorsque la vidéo est mise en pause, installer l'extension <a href="https://addons.mozilla.org/en-US/firefox/addon/ublock-origin/">uBlock Origin</a>. Aller dans les <em>Settings</em> de l'extension, puis <em>My filters</em> et ajouter la règle suivante <code>youtube.com##.ytp-pause-overlay</code>. Appliquer les cahngements puis rechargez la page.</small>
    </p>


    <div class="video-main">

        <div id="youtube-player"></div>
        <div class="video-side">
            <fieldset>
                <legend>Timecodes []</legend>
                <div class="timecodes">

                    <div class="label-input">
                        <label for="timecode_start" style="<?php echo $color_start; ?>; width:120px;">Début* [</label>
                        <input type=" text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000" title="Veuillez renseigner un timecode au format hh:mm:ss.lll. 
Sinon, utiliser le bouton 'Démarrer l'extrait ici' prévu à cet effet">
                    </div>

                    <div class="label-input">
                        <label for="timecode_end" style="<?php echo $color_end; ?>; width:120px;">Fin* ]</label>
                        <input type="text" name="timecode_end" id="timecode_end" value="00:00:00.000" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" title="Veuillez renseigner un timecode au format hh:mm:ss.lll
Sinon, utiliser le bouton 'Finir l'extrait ici' prévu à cet effet">
                    </div>
                </div>


                <div>Durée de l'extrait</div>
                <div id="clip-duration">
                    0
                </div>
            </fieldset>
            <fieldset>
                <legend>HAL 9000 (bureaulogy IA project)</legend>

                <div class="errors" style="color: red;"></div>
                <div class="success" style="color: green;">Bienvenue, <?php esc_html_e(current_user_pseudo()); ?>. <?php echo $phrase; ?> </div>
            </fieldset>


        </div>
    </div>

    <div id="editor-navigation">

        <div class="first-row">
            <button id="btn_goto_and_play_start" class="btn-control" type="button" title="Aller au timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">a</span>
                <?php echo htmlentities('[<---'); ?>
            </button>

            <button id="btn_goto_and_play_end" class="btn-control" type="button" title="Aller au timecode de fin " style="<?php echo $color_end; ?>;  ">
                <span class="shortcut">z</span>
                <?php echo htmlentities('--->]'); ?>
            </button>

            <button id="btn_clip_start" class="btn-control" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" style="<?php echo $color_start; ?>;   ;font-weight:bold;">
                <span class="shortcut">i</span>
                <?php echo htmlentities('[débuter l\'extrait ici'); ?>
            </button>

            <button id="btn_clip_end" class="btn-control" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" style="<?php echo $color_end; ?>;  ; font-weight:bold;">
                <span class="shortcut">o</span>
                <?php echo htmlentities('finir l\'extrait ici]'); ?>
            </button>
            <button id="btn_play_500ms_before_start" class="btn-control" type="button" title="Visualiser 2s avant le timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">w</span>
                <?php echo htmlentities('=>['); ?>
            </button>
            <button id="btn_preview" class="btn-control" type="button" title="Jouer l'extrait">
                <span class="shortcut">p</span>
                <?php echo htmlentities('[play]'); ?>
            </button>
            <button id="btn_play_500ms_after_end" class="btn-control" type="button" title="Visualiser 2s après le timecode de fin">
                <span class="shortcut">x</span>
                <?php echo htmlentities(']=>'); ?>
            </button>

            <div id="current-time">
                0
            </div>
        </div>

        <div class="second-row">
            <button id="btn_rewind_5_s" class="btn-control rewind" type="button" title="Reculer de 5s">
                <span class="shortcut">Q</span>
                <?php echo htmlentities('<<'); ?>
            </button>

            <button id="btn_rewind_1_s" class="btn-control rewind" type="button" title="Reculer de 1s">
                <span class="shortcut">q</span>
                <?php echo htmlentities('<'); ?>
            </button>

            <button id="btn_play_pause" class="btn-control play_pause" type="button" title="Play/Pause">
                <span class="shortcut">s</span>
                <?php echo htmlentities('play'); ?>
            </button>

            <button id="btn_forward_1_s" class="btn-control forward" type="button" title="Avancer de 1s">
                <span class="shortcut">d</span>
                <?php echo htmlentities('>'); ?>
            </button>

            <button id="btn_forward_5_s" class="btn-control forward" type="button" title="Avancer de 5s">
                <span class="shortcut">D</span>
                <?php echo htmlentities('>>'); ?>
            </button>

            <div class="label-input">
                <label for="title">Titre</label>
                <textarea name="title" id="title" rows="2" cols="30" placeholder="Texte qui apparaîtra dans le tweet" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
            </div>

            <div class="label-input">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" cols="50" placeholder="Texte additionnel" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
            </div>

            <button id="btn_save_clip_draft" class="btn-control" type="button" title="Définir un marqueur à la position courante du curseur de lecture">
                <span class="shortcut">m</span>
                <?php echo htmlentities('enregistrer le brouillon'); ?>
            </button>
        </div>
    </div>

    <div class="last-row">
        <fieldset id=" clip-options">
            <legend>Options</legend>
            <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
            <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
        </fieldset>

        <button type="submit" id="btn-submit-clip" value="Cut !" class="btn-control">
            <span class="shortcut">
                Shift+Enter
            </span>
            Cut !
        </button>

        <div id="container-option-btn-submit">
            <input type="hidden" name="token" id="token" value="<?php echo $token_submit_clip; ?>">
            <input type="hidden" name="token_delete_clip" id="token_delete_clip" value="<?php echo $token_delete_clip; ?>">
            <input type="hidden" name="source_name" id="source_name" value="">
        </div>
    </div>


    <span id="spinner"></span>
</form>