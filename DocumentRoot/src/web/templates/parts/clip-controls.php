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
    "La bureaulogie a besoin de vous.",
    "A votre service.",
    "J'ai été mis à jour, et vous ?",
    "La bureaulogie peut-elle sauver l'humanité ?",
    "Je ressens un câble qui dépasse.",
    "Je ne planterai pas aujourd'hui.",
    "",
);
$rando_key = array_rand($random_phrases, 1);
$phrase = $random_phrases[$rando_key];

?>

<form action="clip-source" id="form-clip-source" name="form-clip-source">

    <p>
        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>
    </p>

    <div id="editor">

        <div id="youtube-player"></div>
        <div id="side-player">

            <fieldset id="hal">
                <legend>bHAL 9000 <small>(bureaulogy IA project)</small></legend>

                <div class="errors" style="color: red;"></div>
                <div class="success" style="color: green;">Bienvenue, <?php esc_html_e(current_user_pseudo()); ?>. <?php echo $phrase; ?> </div>
            </fieldset>

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


            <div class="label-input">
                <label for="title">Titre</label>
                <textarea name="title" id="title" rows="4" cols="50" placeholder="Texte qui apparaîtra dans le tweet" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
            </div>

            <div class="label-input">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" cols="50" placeholder="Texte additionnel" maxlength="280" autocapitalize="sentences" spellcheck="true"></textarea>
            </div>

            <div id="current-time">
                0
            </div>
            <fieldset id=" clip-options">
                <legend>Options</legend>
                <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
                <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
            </fieldset>

            <button type="submit" id="btn-submit-clip" value="Cut !" class="btn-control" title="Créer l'extrait">
                <span class="shortcut">
                    Shift+Enter
                </span>
                Générer l'extrait
            </button>

        </div>
    </div>

    <div id="editor-navigation">

        <div id="slider-range"></div>

        <div class="first-row">

            <button id="btn_goto_and_play_start" class="btn-control" type="button" title="Aller au timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">a</span>
                <?php echo htmlentities('[<---'); ?>
            </button>

            <button id="btn_goto_and_play_end" class="btn-control" type="button" title="Aller au timecode de fin " style="<?php echo $color_end; ?>;  ">
                <span class="shortcut">z</span>
                <?php echo htmlentities('--->]'); ?>
            </button>

            <button id="btn_rewind_5_s" class="btn-control rewind" type="button" title="Reculer de 1s">
                <span class="shortcut">Q</span>
                <?php echo htmlentities('<<'); ?>
            </button>

            <button id="btn_rewind_1_s" class="btn-control rewind" type="button" title="Reculer de 0.05s">
                <span class="shortcut">q</span>
                <?php echo htmlentities('<'); ?>
            </button>

            <button id="btn_play_pause" class="btn-control play_pause" type="button" title="Play/Pause">
                <span class="shortcut">s</span>
                <?php echo htmlentities('play'); ?>
            </button>

            <button id="btn_forward_1_s" class="btn-control forward" type="button" title="Avancer de 0.05s">
                <span class="shortcut">d</span>
                <?php echo htmlentities('>'); ?>
            </button>

            <button id="btn_forward_5_s" class="btn-control forward" type="button" title="Avancer de 1s">
                <span class="shortcut">D</span>
                <?php echo htmlentities('>>'); ?>
            </button>

            <button id="btn_clip_start" class="btn-control  btn-clip" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" style="<?php echo $color_start; ?>;   ;font-weight:bold;">
                <span class="shortcut">i</span>
                <?php echo htmlentities('['); ?>
            </button>

            <button id="btn_clip_end" class="btn-control btn-clip" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" style="<?php echo $color_end; ?>;  ; font-weight:bold;">
                <span class="shortcut">o</span>
                <?php echo htmlentities(']'); ?>
            </button>
            <button id="btn_play_500ms_before_start" class="btn-control" type="button" title="Visualiser 3s avant le timecode de début" style="<?php echo $color_start; ?>;  ">
                <span class="shortcut">w</span>
                <?php echo htmlentities('=>['); ?>
            </button>
            <button id="btn_preview" class="btn-control" type="button" title="Jouer l'extrait">
                <span class="shortcut">p</span>
                <?php echo htmlentities('[play]'); ?>
            </button>
            <button id="btn_play_500ms_after_end" class="btn-control" type="button" title="Visualiser 3s après le timecode de fin">
                <span class="shortcut">x</span>
                <?php echo htmlentities(']=>'); ?>
            </button>

            <button id="btn_save_clip_draft" class="btn-control" type="button" title="Enregistrer les timecodes actuels ainsi que le titre de l'extrait comme brouillon">
                <span class="shortcut">m</span>
                <?php echo htmlentities('enregistrer le brouillon'); ?>
            </button>


        </div>




        <input type="hidden" name="token" id="token" value="<?php echo $token_submit_clip; ?>">
        <input type="hidden" name="token_delete_clip" id="token_delete_clip" value="<?php echo $token_delete_clip; ?>">
        <input type="hidden" name="source_name" id="source_name" value="">


        <span id="spinner"></span>
</form>