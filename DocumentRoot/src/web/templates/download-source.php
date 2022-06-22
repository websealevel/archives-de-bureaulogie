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
 * Délivre un jeton pour consommer l'api au compte utilisateur
 * Fait office de nonce (previent CSRF, un jeton pour demander un téléchargement)
 */
$account = from_session('account_id');
$token = register_api_token($account);
?>

<?php present_header(); ?>

<p>Les vidéos <em>sources</em> sont les vidéos originales et complètes à partir desquelles les extraits peuvent être réalisés.</p>



<h2>Ajouter une vidéo source aux archives</h2>

<div class="errors" style="color: red;"></div>
<main class="form-add-source">
    <form action="" method="POST" id="form-download">

        <small>Les champs marqués d'un asterisque(*) sont obligatoires</small>

        <p>
            <label for="series">Choisissez la série à laquelle appartient la source <span class="required">*</span></label>
            <select name="series" id="">
                <option value="le-tribunal-des-bureaux" selected>Le tribunal des bureaux</option>
            </select>
        </p>

        <p>
            <label for="name">Choisissez un identifiant court pour cette vidéo (un mot ou un nombre en minuscule) <span class="required">*</span></label>
            <input type="text" name="name" placeholder="ex: 3" pattern="[a-z0-9#]{1,25}" title="un nom en caractères alphanumériques, sans espaces (utiliser des hyphens à la place), d'1 caractère min" value="" required>

        </p>
        <p>
            <label for="source_url">Copiez/collez l'url de la vidéo youtube <span class="required">*</span></label>
            <input type="url" name="source_url" title="l'url complète de la vidéo youtube" value="" required>
        </p>
        <p name="preview_source">
            <iframe width="420" height="315" src="">
            </iframe>
        </p>
        <p>
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="fax" value="">
            <input type="button" value="Ajouter" id="submit">
        </p>
    </form>

    <h3>Téléchargements en cours</h3>
    <ul id="active_downloads">
    </ul>
    <h3>Historique</h3>
    <ul id="downloads-history">
        <?php esc_download_history_items_e();  ?>
    </ul>


    <h2>Contenu des archives</h2>
    <?php esc_html_list_sources_e(show_data: array('details')); ?>

</main>

<?php present_footer(array('jquery-min', 'template-download-source')); ?>