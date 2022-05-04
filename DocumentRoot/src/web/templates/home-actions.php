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

<a href="">Éditer un extrait</a>
<a href="">Télécharger une nouvelle source</a>
<a href="/log-out">Se déconnecter</a>