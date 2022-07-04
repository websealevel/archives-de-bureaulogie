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
require_once __DIR__ . '/../api/token.php';


if (!current_user_can('submit_clip'))
    redirect('/', 'notices', array(new Notice('Vous devez être authentifié pour soumettre un clip', NoticeStatus::Error)));


/**
 * Délivre un jeton pour consommer l'api au compte utilisateur
 * Fait office de nonce (previent CSRF, un jeton pour demander un téléchargement)
 */
$account = from_session('account_id');
$token = register_api_token($account, 'submit_clip');

?>

<?php present_header(); ?>

<main class="form-clip">

    <h2>Soumettre un extrait</h2>

    <form action="">
        <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
    </form>




    <form action="clip-source" id="form-clip-source" name="form-clip-source">
        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>
        <div class="errors" style="color: red;"></div>
        <div class="success" style="color: green;"></div>
        <div class="video-main">

            <div class="video">
                <video id="video-source" width="1000px" height="580px" controls>
                    <source src="" type="video/mp4">
                    Votre navigateur ne supporte pas le tag video HTML5 :(
                </video>
            </div>

            <div class="video-side">
                <div>
                    <div id="current-time">
                        00:00:00.000
                    </div>
                </div>

                <div>
                    <label for="clip-duration">Durée de l'extrait</label>
                    <div id="clip-duration" name="clip-duration">
                        0
                    </div>
                </div>

                <div>
                    <div class="label-input">
                        <label for="timecode_start" style="width:120px" ;>Début*</label>
                        <input type="text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000" title="Veuillez renseigner un timecode au format hh:mm:ss.lll. 
Sinon, utiliser le bouton 'Démarrer l'extrait ici' prévu à cet effet" class="btn-edition">
                    </div>
                    <button name="" id="" class="btn-edition" type="button" title="">
                        <div class="shortcut"></div>
                        <?php echo htmlentities('Aller'); ?>
                    </button>


                </div>


                <div>
                    <div class="label-input">
                        <label for="timecode_end" style="width:120px">Fin*</label>
                        <input type="text" name="timecode_end" id="timecode_end" value="00:00:00.000" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" title="Veuillez renseigner un timecode au format hh:mm:ss.lll
Sinon, utiliser le bouton 'Finir l'extrait ici' prévu à cet effet" class="btn-edition">
                    </div>


                    <button name="" id="" class="btn-edition" type="button" title="">
                        <div class="shortcut"></div>
                        <?php echo htmlentities('Aller'); ?>
                    </button>
                </div>


            </div>
        </div>

        <?php require_once 'parts/clip-controls.php' ?>

        <fieldset id="fieldset-edition">

            <legend>Métadonnées</legend>

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
        </fieldset>

        <div id="container-option-btn-submit">
            <fieldset id="clip-options">
                <legend>Options</legend>

                <div>
                    <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
                    <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
                </div>

                <div>
                    <label for="tail_duration_in_s" title="La durée de traîne correspond au nombre de secondes de vidéo après le timecode de fin qui seront prévisualisées en cliquant sur 'Prévisualiser la traîne'">Durée de traîne (s)</label>

                    <input type="number" name="tail_duration_in_s" id="tail_duration_in_s" value="4" min="0" max="20" title="La durée de traîne correspond au nombre de secondes de vidéo après le timecode de fin qui seront prévisualisées en cliquant sur 'Prévisualiser la traîne'">
                </div>
            </fieldset>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="source_name" id="source_name" value="">




            <button name="btn_set_marker" id="btn_set_marker" class="btn-edition" type="button" title="Définir un marqueur à la position courante du curseur de lecture">
                <div class="shortcut">m</div>
                <?php echo htmlentities('Marquer pour plus tard'); ?>
            </button>

            <div class="container-btn-submit-clip">
                <button type="submit" id="btn-submit-clip" value="Cut !" class="btn-edition">
                    <div class="shortcut">
                        Shift+Enter
                    </div>
                    Cut !
                </button>
            </div>
        </div>
        <span id="spinner"></span>
    </form>
</main>

<side class="two-col-side">
    <div>
        <?php esc_html_list_clips_of_source_e(show_data: array('details'), name_attr: 'list-clips-on-current-source'); ?>
    </div>

    <div>
        <h2>Vos marqueurs</h2>
        <ul name="list-markers" id="list-markers"></ul>
    </div>
</side>


<?php present_footer(array('jquery-min', 'template-clip')); ?>