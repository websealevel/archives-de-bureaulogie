<fieldset id="fieldset-controls">
    <legend>Contrôles</legend>
    <input name=" btn_rewind_5_s" id="btn_rewind_5_s" class="btn-controls rewind" type="button" title="Reculer de 5s" value="<<" title="reculer de 5s">

    <input name=" btn_rewind_1_s" id="btn_rewind_1_s" class="btn-controls rewind" type="button" value="<" title="Reculer de 1s (Raccourci : flèche gauche)">

    <input name=" btn_forward_1_s" id="btn_forward_1_s" type="button" title="Avancer de 1s" value=">" class="btn-controls forward" title="avancer de 1s (flèche droite)">
    <input name=" btn_forward_5_s" id="btn_forward_5_s" type="button" title="Avancer de 5s" value=">>" class="btn-controls forward" title="avancer de 5s">

    <div id="edition-actions">
        <input name=" btn_clip_start" id="btn_clip_start" type="button" class="btn-edition" title="Définir la position courante du curseur de lecture comme timecode de départ de l'extrait" value="Démarrer l'extrait ici">

        <input name="bt_clip_end" id="btn_clip_end" type="button" class="btn-edition" title="Définir la position courante du curseur de lecture comme timecode de fin de l'extrait" value="Finir l'extrait ici">

        <input type="button" id="btn_preview" value="Prévisualiser" class="btn-edition">

        <input type="button" id="btn_preview_tail" value="Prévisualiser la traîne" class="btn-edition" title="La traine correspond à la portion située juste après le timecode de fin, afin de mieux visualiser la fin du cut">
    </div>

    <div id="clip-duration">
        0
    </div>

    <div class="label-input">
        <label for="timecode_start">Début*</label>
        <input type="text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000" title="Veuillez renseigner un timecode au format hh:mm:ss.lll. 
Sinon, utiliser le bouton 'Démarrer l'extrait ici' prévu à cet effet">
    </div>

    <div class="label-input">
        <label for="timecode_end">Fin*</label>
        <input type="text" name="timecode_end" id="timecode_end" value="00:00:12.000" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" title="Veuillez renseigner un timecode au format hh:mm:ss.lll
Sinon, utiliser le bouton 'Finir l'extrait ici' prévu à cet effet">
    </div>


</fieldset>