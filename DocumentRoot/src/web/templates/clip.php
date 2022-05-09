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

session_start();

if (!current_user_can('submit_clip'))
    redirect('/');
//Creer un extrait, post en AJAX via l'API de l'appli, réponse
?>

<?php present_header(); ?>

<h2>Créer un extrait</h2>
<a href="/">Retour</a>

<p>Lore ipsum</p>

<?php
// OK !!
$url = "http://back.ackboo.test/src/web/templates/the_hustle.mp4";
// OK !!
$url_extraits = 'src/web/templates/the_hustle.mp4';
// OK !!
$url_extraits = 'extraits/le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4';
// !! OK
$url_official = web_clip_path('le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4');
dump($url_official);
?>


<video width="320" height="240" controls>
    <source src="<?php echo web_clip_path('le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4'); ?>" type="video/mp4">
    Your browser does not support the video tag.
</video>

<?php present_footer(); ?>