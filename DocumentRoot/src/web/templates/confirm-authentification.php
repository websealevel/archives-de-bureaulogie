<?php

/**
 * Page de demande de réauthentification pour les actions délicates
 *
 * @link
 *
 * @package wsl 
 */
present_header();

?>

<h1>Êtes-vous bien <i>vous</i> ?</h1>

<p>Vous souhaitez effectuer une action sensible. Veuillez saisir votre mot de passe à nouveau s'il vous plait.</p>

<form action="confirm-authentification" method="post">
    <label for="password">Mot de passe:</label>
    <input type="password" name="password">
    <input type="submit" value="Valider">
</form>

<?php


present_footer();
