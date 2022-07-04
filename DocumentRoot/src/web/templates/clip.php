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

    <form action="clip-source">
        <?php esc_sources_to_html_select_e(show_data: array('label')); ?>
    </form>


    <form action="clip-source" id="form-clip-source" name="form-clip-source">
        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>
        <div class="errors" style="color: red;"></div>
        <div class="success" style="color: green;"></div>


        <div class="video-main">

            <div class="video">
                <video id="video-source" width="1000" height="580" controls>
                    <source src="" type="video/mp4">
                    Votre navigateur ne supporte pas le tag video HTML5 :(
                </video>
            </div>

            <div class="video-side">
                <fieldset>
                    <legend>Durée de l'extrait</legend>
                    <div id="clip-duration">
                        0
                    </div>
                </fieldset>

                <div id="current-time">
                    00:00:00.000
                </div>

                <div class="timecodes">

                    <div class="label-input">
                        <label for="timecode_start" style="width:120px">Début*</label>
                        <input type="text" name="timecode_start" id="timecode_start" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" value="00:00:00.000" title="Veuillez renseigner un timecode au format hh:mm:ss.lll. 
Sinon, utiliser le bouton 'Démarrer l'extrait ici' prévu à cet effet" class="btn-edition">
                    </div>
                    <button class="btn-edition" type="button" title="">
                        <span class="shortcut"></span>
                        <?php echo htmlentities('Aller'); ?>
                    </button>

                    <div class="label-input">
                        <label for="timecode_end" style="width:120px;">Fin*</label>
                        <input type="text" name="timecode_end" id="timecode_end" value="00:00:00.000" pattern="[0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}" title="Veuillez renseigner un timecode au format hh:mm:ss.lll
Sinon, utiliser le bouton 'Finir l'extrait ici' prévu à cet effet" class="btn-edition">
                    </div>


                    <button name="" id="" class="btn-edition" type="button" title="">
                        <span class="shortcut"></span>
                        <?php echo htmlentities('Aller'); ?>
                    </button>
                </div>

                <button name="btn_rewind_5_s" id="btn_rewind_5_s" class="btn-control rewind" type="button" title="Reculer de 5s">
                    <span class="shortcut">Q</span>
                    <?php echo htmlentities('<<'); ?>
                </button>

                <button name="btn_rewind_1_s" id="btn_rewind_1_s" class="btn-control rewind" type="button" title="Reculer de 1s">
                    <span class="shortcut">q</span>
                    <?php echo htmlentities('<'); ?>
                </button>

                <button name="btn_play_pause" id="btn_play_pause" class="btn-control play_pause" type="button" title="Play/Pause">
                    <span class="shortcut">s</span>
                    <?php echo htmlentities('play'); ?>
                </button>

                <button name="btn_forward_1_s" id="btn_forward_1_s" class="btn-control forward" type="button" title="Avancer de 1s">
                    <span class="shortcut">d</span>
                    <?php echo htmlentities('>'); ?>
                </button>

                <button name="btn_forward_5_s" id="btn_forward_5_s" class="btn-control forward" type="button" title="Avancer de 5s">
                    <span class="shortcut">D</span>
                    <?php echo htmlentities('>>'); ?>
                </button>

                <button name="btn_goto_and_play_start" id="btn_goto_and_play_start" class="btn-edition" type="button" title="">
                    <span class="shortcut"></span>
                    <?php echo htmlentities('Aller et Jouer au début de l\'extrait'); ?>
                </button>

                <button name="btn_goto_and_play_end" id="btn_goto_and_play_end" class="btn-edition" type="button" title="">
                    <span class="shortcut"></span>
                    <?php echo htmlentities('Aller et Jouer à la fin de l\'extrait'); ?>
                </button>

                <button name="btn_play_500ms_before_start" id="btn_play_500ms_before_start" class="btn-edition" type="button" title="">
                    <span class="shortcut"></span>
                    <?php echo htmlentities('Jouer 500ms avant le début'); ?>
                </button>

                <button name="btn_preview_tail" id="btn_preview_tail" class="btn-edition" type="button" title="La traine correspond à la portion située juste après le timecode de fin, afin de mieux visualiser la fin du cut">
                    <span class="shortcut">o</span>
                    <?php echo htmlentities('Jouer 500ms après la fin'); ?>
                </button>
            </div>
        </div>

        <?php require_once 'parts/clip-controls.php' ?>

        <div id="container-option-btn-submit">

            <fieldset id="clip-options">
                <legend>Options</legend>

                <div>
                    <label for="checkbox_loop_preview">Prévisualisation en boucle</label>
                    <input type="checkbox" name="checkbox_loop_preview" id="checkbox_loop_preview">
                </div>

            </fieldset>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="source_name" id="source_name" value="">


        </div>

        <span id="spinner"></span>
    </form>
</main>

<nav class="two-col-side">
    <section>
        <?php esc_html_list_clips_of_source_e(show_data: array('details'), name_attr: 'list-clips-on-current-source'); ?>
    </section>

    <section>
        <h2>Vos brouillons</h2>
        <ul id="list-markers"></ul>
    </section>
</nav>


<?php present_footer(array('jquery-min', 'template-clip')); ?>