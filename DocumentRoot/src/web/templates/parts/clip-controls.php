<fieldset id="fieldset-controls">

    <legend>Édition</legend>

    <button name="btn_clip_start" id="btn_clip_start" class="btn-edition" type="button" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait">
        <span class="shortcut">k</span>
        <?php echo htmlentities('Démarrer l\'extrait ici'); ?>
    </button>

    <button name="btn_clip_end" id="btn_clip_end" class="btn-edition" type="button" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait">
        <span class="shortcut">l</span>
        <?php echo htmlentities('Terminer l\'extrait ici'); ?>
    </button>

    <button name="btn_preview" id="btn_preview" class="btn-edition" type="button" title="Jouer l'extrait">
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

    <button name="btn_set_marker" id="btn_set_marker" class="btn-edition" type="button" title="Définir un marqueur à la position courante du curseur de lecture">
        <span class="shortcut">m</span>
        <?php echo htmlentities('Enregistrer le brouillon'); ?>
    </button>

    <div class="container-btn-submit-clip">
        <button type="submit" id="btn-submit-clip" value="Cut !" class="btn-edition">
            <span class="shortcut">
                Shift+Enter
            </span>
            Cut !
        </button>
    </div>


</fieldset>