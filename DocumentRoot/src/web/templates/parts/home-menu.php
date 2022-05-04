<?php

/**
 * Le menu de la page d'accueil pour un utilisateur authentifié
 * @link
 *
 * @package wsl 
 */

require __DIR__ . '/../../current-user.php';

session_start();
if (!is_current_user_logged_in())
    redirect('/');
?>

<h2>Bienvenue !</h2>

<a href="">Éditer un extrait</a>
<a href="">Télécharger une nouvelle source</a>