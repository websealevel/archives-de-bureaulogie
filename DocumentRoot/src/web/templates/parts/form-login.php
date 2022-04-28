<?php

/**
 * Le formulaire de login
 *
 * @package wsl 
 */
?>

<div class="form-login">
    <form action="authentificate" method="post">
        <label for="login">Pseudo </label>
        <input type="text" id="login" name="login">
        <label for="password">Mot de passe </label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Connexion">
    </form>
</div>