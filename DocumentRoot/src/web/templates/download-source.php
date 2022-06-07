<?php

/**
 * Page gérant le téléchargement de nouvelles vidéos sources
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Vendor
 */
require_once __DIR__ . '/../../../vendor/autoload.php';

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../core-interface.php';
require_once __DIR__ . '/../database/repository-token.php';

if (!current_user_can('add_source'))
    redirect('/', 'notices', array(
        new Notice('Vous n\'avez pas l\'autorisation d\'ajouter une source', NoticeStatus::Error)
    ));

/**
 * Cree un token pour consommer l'API
 */
$account = from_session('account_id');

// /**
//  * Fait office de nonce (previent CSRF, un jeton pour demander un téléchargement)
//  */
// $token = register_api_token($account);
dump($_SESSION);

?>

<?php present_header(); ?>

<h2>Importer une nouvelle vidéo source aux archives</h2>


<p>Les vidéos <em>sources</em> sont les vidéos originales et complètes à partir desquelles les extraits pourront être réalisés.</p>

<h3>Liste des sources présentes dans les archives</h3>
<?php esc_html_list_sources_e(); ?>

<h3>Importer</h3>
<main class="form-add-source">
    <form action="" method="POST" id="form-download">

        <div class="form-note">Les champs marqués d'un asterisque sont obligatoires</div>

        <div>
            <?php esc_html_form_error_msg_e('series', 'form_errors'); ?>
            <label for="series">Choisissez la série à laquelle appartient la source <span class="required">*</span></label>
            <select name="series" id="">
                <option value="le-tribunal-des-bureaux" selected>Le tribunal des bureaux</option>
            </select>
        </div>

        <div>
            <?php esc_html_form_error_msg_e('name', 'form_errors'); ?>
            <label for="name">Choisissez un identifiant court pour cette vidéo (un mot ou un nombre en minuscule) <span class="required">*</span></label>
            <input type="text" name="name" placeholder="ex: 3" pattern="[a-z0-9#]{1,25}" title="un nom en caractères alphanumériques, sans espaces (utiliser des hyphens à la place), d'1 caractère min" value="1" required>

        </div>
        <div>
            <?php esc_html_form_error_msg_e('source_url', 'form_errors'); ?>
            <label for="source_url">Copiez/collez l'url de la vidéo youtube <span class="required">*</span></label>
            <input type="url" name="source_url" title="l'url complète de la vidéo youtube" value="https://www.youtube.com/watch?v=YglE-FnSd3g" required>
        </div>
        <div name="preview_source">
            <p>Preview</p>
            <iframe width="420" height="315" src="">
            </iframe>
        </div>
        <div>
            <input type="submit" value="ajouter aux téléchargements">
        </div>
    </form>

    <h3>Téléchargements en cours</h3>
    <ul id="active_downloads">
    </ul>
    <h3>Historique des téléchargements</h3>
    <ul id="downloads-history">
        <?php esc_download_history_items_e();  ?>
    </ul>
</main>

<?php present_footer(array('jquery-min', 'template-download-source')); ?>