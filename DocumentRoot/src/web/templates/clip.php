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


    <div id="current-time">
        00:00:00.000
    </div>
    <div class="videos">
        <fieldset id="fieldset-video-source">
            <legend>
                <h3>Source</h3>
            </legend>
            <div class="video-source">
                <video id="video-source" width="100%" controls>
                    <source src="" type="video/mp4">
                    Votre navigateur ne supporte pas le tag video HTML5 :(
                </video>
            </div>
        </fieldset>

        <fieldset id="fieldset-video-clip" width="100%">
            <legend>
                <h3>Prévisualisation</h3>
            </legend>
            <div class="video-clip">
                <video id="video-clip" width="100%">
                    <source src="" type="video/mp4">
                    Votre navigateur ne supporte pas le tag video HTML5 :(
                </video>
            </div>
        </fieldset>
    </div>

    <div class="errors" style="color: red;"></div>

    <form action="clip-source" id="form-clip-source" name="form-clip-source">

        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>

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
        <div class="container-btn-submit-clip">

            <button type="submit" id="btn-submit-clip" value="Cut !" class="btn-edition">

                <div class="shortcut">
                    Shift+Enter
                </div>
                Cut !

            </button>

        </div>
        <span id="spinner"></span>
    </form>
</main>

<side>
    <h2>Extraits existants</h2>
    <?php esc_html_list_clips_of_source_e(show_data: array('details')); ?>
    <div id="list-clips-on-current-source"></div>
</side>


<?php present_footer(array('jquery-min', 'template-clip')); ?>