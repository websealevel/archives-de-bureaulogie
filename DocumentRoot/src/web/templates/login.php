<?php

/**
 * Le formulaire de login
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../environment.php';

?>

<?php present_header(); ?>

<h2>Se connecter</h2>
<div class="form-login">
    <form action="log-in" method="POST">
        <div>
            <label for="login">Login </label>
            <input type="text" id="login" name="login" value="paul">
            <div class="error-message"><?php esc_html_form_error_msg_e('login', 'form_errors') ?></div>
        </div>
        <div>
            <label for="password">Mot de passe </label>
            <input type="password" id="password" name="password" value="password">
            <div class="error-message"><?php esc_html_form_error_msg_e('password', 'form_errors') ?></div>
        </div>
        <input type="submit" value="Connexion">
    </form>
</div>

<?php if (is_signup_activated()) : ?>
    <div class="sign-up">
        <p>Pas encore de compte ? <a href="sign-up">S'inscrire</a></p>
    </div>
<?php endif; ?>
<?php present_footer(); ?>