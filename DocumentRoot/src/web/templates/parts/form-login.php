<?php

/**
 * Le formulaire de login
 *
 * @package wsl 
 */
?>

<?php dump($_SESSION); ?>
<h2>Se connecter</h2>
<div class="form-login">
    <form action="log-in" method="POST">
        <div>
            <label for="login">Login </label>
            <input type="text" id="login" name="login">
            <div class="error-message"><?php esc_html_form_error_msg_e('login', 'form_errors') ?></div>
        </div>
        <div>
            <label for="password">Mot de passe </label>
            <input type="password" id="password" name="password">
            <div class="error-message"><?php esc_html_form_error_msg_e('password', 'form_errors') ?></div>
        </div>
        <input type="submit" value="Connexion">
    </form>
</div>

<div class="sign-up">
    <p>Pas encore de compte ? <a href="sign-up">S'inscrire à l'ULB</a></p>
</div>