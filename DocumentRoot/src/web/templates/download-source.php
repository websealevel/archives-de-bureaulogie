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

<h2>Ajouter une nouvelle vidéo source à la bibliothèque</h2>

<div>
    <a href="/">Retour</a>
</div>


<?php esc_html_list_sources_e(); ?>

<h3>Ajouter</h3>
<form action="download-source" method="post">
    <label for="source_url">Renseigner l'url youtube de la vidéo</label>
    <input type="url" name="source_url">
    <input type="submit" value="Télécharger sur le serveur">
</form>

<div name="preview_source">
    <video width="320" height="240" controls>
        <source src="" type="video/mp4">
        Votre navigateur ne supporte pas le tag video HTML5 :(
    </video>
</div>


<?php present_footer(); ?>