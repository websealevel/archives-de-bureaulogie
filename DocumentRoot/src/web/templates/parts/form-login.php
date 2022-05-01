<?php

/**
 * Le formulaire de login
 *
 * @package wsl 
 */
?>

<h2>Connexion</h2>
<div class="form-login">
    <form action="authentificate" method="post">
        <label for="login">Pseudo </label>
        <input type="text" id="login" name="login">
        <label for="password">Mot de passe </label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Connexion">
    </form>
</div>

<div class="sign-up">
    <p>Pas encore de compte, <a href="sign-up">créez en un</a>.</p>
</div>