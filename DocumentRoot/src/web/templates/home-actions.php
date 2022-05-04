<?php

/**
 * Le menu de la page d'accueil pour un utilisateur authentifié
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../current-user.php';

if (!is_current_user_logged_in())
    redirect('/');
?>

<h2>Bienvenue !</h2>
<ul>
    <li><a href="clip">Éditer un extrait</a></li>
    <li><a href="">Télécharger une nouvelle source</a></li>
</ul>


<a href="/log-out">Se déconnecter</a>