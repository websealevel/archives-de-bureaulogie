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

<h3>Archives vidéos</h3>

<ul>
    <li><a href="clip">Créer un nouvel extrait</a></li>
</ul>

<h3>Archives bibliographiques</h3>
<ul>
    <li><a href="clip">Ajouter une référence bibliographique</a></li>
</ul>

<a href="/log-out">Se déconnecter</a>