<?php

/**
 * Template form de création de compte
 * @link
 *
 * @package wsl 
 */
?>

<?php present_header(); ?>

<h2>Création de votre compte</h2>
<form action="sign-up" method="post">
    <label for="pseudo">Pseudo</label>
    <input type="text" name="pseudo">
    <label for="email">Email</label>
    <input type="email" name="email">
    <label for="password">Mot de passe</label>
    <input type="password" name="password">
    <label for="password_confirmation">Confirmer votre mot de passe</label>
    <input type="password" name="password_confirmation">
    <input type="submit" value="Créer mon compte">
</form>

<?php present_footer(); ?>
