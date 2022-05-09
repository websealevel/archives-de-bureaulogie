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
require_once __DIR__ . '/../core-interface.php';

session_start();

if (!current_user_can('submit_clip'))
    redirect('/');
//Creer un extrait, post en AJAX via l'API de l'appli, réponse
?>

<?php present_header(); ?>

<h2>Créer un extrait</h2>

<a href="/">Retour</a>

<form action="">
    <?php esc_sources_to_html_select_e(); ?>
</form>

<div name="preview_source">
    <video width="320" height="240" controls>
        <source src="<?php echo esc_web_clip_path_url_e('le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4'); ?>" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>

</div>

<div name="preview_clip">
    <video width="320" height="240" controls>
        <source src="<?php echo esc_web_clip_path_url_e('le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4'); ?>" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>
</div>


<?php present_footer(array('template-clip')); ?>