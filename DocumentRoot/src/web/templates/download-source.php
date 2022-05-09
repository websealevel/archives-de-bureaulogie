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
//Creer un extrait, post en AJAX via l'API de l'appli, réponse
?>

<?php present_header(); ?>

<h2>Ajouter une nouvelle vidéo source</h2>

<a href="/">Retour</a>

<form action="">
</form>

<div name="preview_source">
    <video width="320" height="240" controls>
        <source src="<?php ?>" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>
</div>


<?php present_footer(); ?>