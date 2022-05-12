<?php

/**
 * Le menu de la page d'accueil pour un utilisateur authentifié
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../current-user.php';
?>
<?php present_header()?>

<h2>Bienvenue !</h2>

<h3>Archives vidéos</h3>

<ul>
    <?php if (current_user_can('submit_clip')) :  ?>
        <li><a href="clip">Créer un nouvel extrait</a></li>
    <?php endif;  ?>

    <?php if (current_user_can('add_source')) :  ?>
        <li><a href="download-source">Ajouter une nouvelle vidéo source</a></li>
    <?php endif;  ?>

</ul>

<h3>Archives bibliographiques</h3>
<ul>
    <?php if (current_user_can('submit_reference')) :  ?>
        <li><a href="submit_ref">Ajouter une référence bibliographique</a></li>
    <?php endif;  ?>
</ul>

<a href="/log-out">Se déconnecter</a>
<?php present_footer(); ?>