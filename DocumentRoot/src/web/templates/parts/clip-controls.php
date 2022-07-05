<?php
$color_start = '';
$color_end = '';
?>

<form action="clip-source" id="form-clip-source" name="form-clip-source">
    <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>



    <div class="video-main">

        <div class="video">
            <video id="video-source" width="100%" height="580" controls>
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas le tag video HTML5 :(
            </video>
        </div>

        <div class="video-side">

            <fieldset>
                <legend>Log</legend>

                <div class="errors" style="color: red;"></div>
                <div class="success" style="color: green;">Bienvenue, <?php esc_html_e(current_user_pseudo()); ?></div>
            </fieldset>

            <fieldset>
                <legend>Durée de l'extrait</legend>
                <div id="clip-duration">
                    0
                </div>
            </fieldset>

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

                    <div id="current-time">
                        00:00:00.000
                    </div>
                </div>
            </fieldset>


        </div>
    </div>



    <div id="editor-navigation">

        <div class="first-row">
            <button id="btn_goto_and_play_start" class="btn-control" type="button" title="Aller au timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">a</span>
                <?php echo htmlentities('[<='); ?>
            </button>

            <button id="btn_goto_and_play_end" class="btn-control" type="button" title="Aller au timecode de fin " style="<?php echo $color_end; ?>;  ">
                <span class="shortcut">z</span>
                <?php echo htmlentities('=>]'); ?>
            </button>

            <button id="btn_play_500ms_before_start" class="btn-control" type="button" title="Visualiser 1.5s avant le timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">w</span>
                <?php echo htmlentities('=>['); ?>
            </button>

            <button id="btn_play_500ms_after_end" class="btn-control" type="button" title="Visualiser 1.5s après le timecode de fin">
                <span class="shortcut">x</span>
                <?php echo htmlentities(']=>'); ?>
            </button>

            <button id="btn_preview" class="btn-control" type="button" title="Jouer l'extrait">
                <span class="shortcut">p</span>
                <?php echo htmlentities('[play]'); ?>
            </button>

            <div id="edition-data">

                <div class="label-input">
                    <label for="title">Titre</label>
                    <textarea name="title" id="title" rows="8" cols="50" placeholder="Texte qui apparaîtra dans le tweet" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
                </div>

                <div class="label-input">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="8" cols="60" placeholder="Texte additionnel" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
                </div>

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

            <button id="btn_clip_start" class="btn-control" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" style="<?php echo $color_start; ?>;   ;font-weight:bold;">
                <span class="shortcut">k</span>
                <?php echo htmlentities('[débuter l\'extrait ici'); ?>
            </button>

            <button id="btn_clip_end" class="btn-control" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" style="<?php echo $color_end; ?>;  ; font-weight:bold;">
                <span class="shortcut">l</span>
                <?php echo htmlentities('finir l\'extrait ici]'); ?>
            </button>

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
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="source_name" id="source_name" value="">
        </div>
    </div>


    <span id="spinner"></span>
</form>