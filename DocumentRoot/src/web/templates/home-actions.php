<?php

/**
 * Le menu de la page d'accueil pour un utilisateur authentifié
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../current-user.php';


?>

<h2>Bienvenue !</h2>

<h3>Archives vidéos</h3>


<ul>
    <?php if (current_user_can('submit_clip')) :  ?>
        <li><a href="clip">Créer un nouvel extrait</a></li>
    <?php endif;  ?>

</ul>

<h3>Archives bibliographiques</h3>
<ul>
    <?php if (current_user_can('submit_reference')) :  ?>
        <li><a href="clip">Ajouter une référence bibliographique</a></li>
    <?php endif;  ?>
</ul>

<a href="/log-out">Se déconnecter</a>