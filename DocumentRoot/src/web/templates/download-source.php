<?php

/**
 * Page gérant le téléchargement de nouvelles vidéos sources
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../core-interface.php';

session_start();

if (!current_user_can('add_source'))
    redirect('/');

?>

<?php present_header(); ?>
<div>
    <a href="/">Retour</a>
</div>

<h2>Ajouter une nouvelle vidéo source à la bibliothèque</h2>

<p>Les vidéos <em>sources</em> sont les vidéos originales et complètes à partir desquelles les extraits pourront être réalisés.</p>

<h3>Sources présentes dans la base</h3>
<?php esc_html_list_sources_e(); ?>

<h3>Ajouter</h3>
<main class="form-add-source">
    <form action="download-source" method="POST">


        <div>
            <?php esc_html_form_error_msg_e('series', 'form_errors'); ?>
            <label for="series">Choisissez la série à laquelle appartient la source</label>
            <select name="series" id="">
                <option value="le-tribunal-des-bureaux" selected>Le tribunal des bureaux</option>
            </select>
        </div>

        <div>
            <?php esc_html_form_error_msg_e('slug', 'form_errors'); ?>
            <label for="slug">Choisissez un identifiant court pour cette vidéo (un mot/nombre en minuscule)</label>
            <input type="text" name="slug" pattern="[a-z0-9]{1,12}" required value="<?php esc_html_from_session_e('form_errors', 'slug'); ?>">
        </div>
        <div>
            <?php esc_html_form_error_msg_e('source_url', 'form_errors'); ?>
            <label for="source_url">Copiez/collez l'url de la vidéo youtube</label>
            <input type="url" name="source_url" value="<?php esc_html_from_session_e('form_errors', 'source_url'); ?>" required>
        </div>
        <div name="preview_source">
            <p>Preview</p>
            <video width="320" height="240" controls>
                <source src="" type="video/mp4">
                Votre navigateur ne supporte pas le tag video HTML5 :(
            </video>
        </div>
        <div>
            <input type="submit" value="Ajouter la source">
        </div>
    </form>
</main>

<?php present_footer(); ?>