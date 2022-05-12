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
    redirect('/', 'notices', array(new Notice('Vous devez être authentifié pour soumettre un clip')));
    
?>

<?php present_header(); ?>

<h2>Créer un extrait</h2>

<a href="/">Retour</a>

<form action="">
    <?php esc_sources_to_html_select_e(); ?>
</form>

<div name="preview_source">
    <video width="320" height="240" controls>
        <source src="<?php esc_video_source_url_e('') ?>" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>
</div>



<?php present_footer(array('template-clip')); ?>