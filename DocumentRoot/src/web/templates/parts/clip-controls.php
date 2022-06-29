<fieldset id="fieldset-controls">

    <legend>Contrôles</legend>

    <button name="btn_rewind_5_s" id="btn_rewind_5_s" class="btn-control rewind" type="button" title="Reculer de 5s">
        <div class="shortcut">Q</div>
        <?php echo htmlentities('<<'); ?>
    </button>

    <button name="btn_rewind_1_s" id="btn_rewind_1_s" class="btn-control rewind" type="button" title="Reculer de 1s">
        <div class="shortcut">q</div>
        <?php echo htmlentities('<'); ?>
    </button>

    <button name="btn_play_pause" id="btn_play_pause" class="btn-control play_pause" type="button" title="Play/Pause">
        <div class="shortcut">s</div>
        <?php echo htmlentities('Play'); ?>
    </button>

    <button name="btn_forward_1_s" id="btn_forward_1_s" class="btn-control forward" type="button" title="Avancer de 1s">
        <div class="shortcut">d</div>
        <?php echo htmlentities('>'); ?>
    </button>

    <button name="btn_forward_5_s" id="btn_forward_5_s" class="btn-control forward" type="button" title="Avancer de 5s">
        <div class="shortcut">D</div>
        <?php echo htmlentities('>>'); ?>
    </button>

    <button name="btn_clip_start" id="btn_clip_start" class="btn-edition" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait">
        <div class="shortcut">k</div>
        <?php echo htmlentities('Démarrer l\'extrait ici'); ?>
    </button>

    <button name="btn_clip_end" id="btn_clip_end" class="btn-edition" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait">
        <div class="shortcut">l</div>
        <?php echo htmlentities('Terminer l\'extrait ici'); ?>
    </button>

    <button name="btn_preview" id="btn_preview" class="btn-edition" type="button" title="Prévisualiser">
        <div class="shortcut">p</div>
        <?php echo htmlentities('Prévisualiser l\'extrait'); ?>
    </button>

    <button name="btn_preview_tail" id="btn_preview_tail" class="btn-edition" type="button" title="La traine correspond à la portion située juste après le timecode de fin, afin de mieux visualiser la fin du cut">
        <div class="shortcut">o</div>
        <?php echo htmlentities('Prévisualiser la traîne'); ?>
    </button>

    <button name="btn_set_marker" id="btn_set_marker" class="btn-edition" type="button" title="Définir un marqueur à la position courante du curseur de lecture">
        <div class="shortcut">m</div>
        <?php echo htmlentities('Marquer pour plus tard'); ?>
    </button>

    <div class="label-input">
        <label for="timecode_start">Début*</label>
        <input type="text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000" title="Veuillez renseigner un timecode au format hh:mm:ss.lll. 
Sinon, utiliser le bouton 'Démarrer l'extrait ici' prévu à cet effet">
    </div>

    <div class="label-input">
        <label for="timecode_end">Fin*</label>
        <input type="text" name="timecode_end" id="timecode_end" value="00:00:00.000" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" title="Veuillez renseigner un timecode au format hh:mm:ss.lll
Sinon, utiliser le bouton 'Finir l'extrait ici' prévu à cet effet">
    </div>

</fieldset>