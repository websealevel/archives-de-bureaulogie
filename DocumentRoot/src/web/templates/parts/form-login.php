<?php

/**
 * Affiche le formulaire de login
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../utils.php';
?>



<h2>Accueil</h2>
<div class="form-login">
    <form action="log-in" method="POST">
        <div>
            <label for="login">pseudo </label>
            <input type="text" id="login" name="login" value="paul">
            <div class="error-message"><?php esc_html_form_error_msg_e('login', 'form_errors') ?></div>
        </div>
        <div>
            <label for="password">mot de passe </label>
            <input type="password" id="password" name="password" value="password">
            <div class="error-message"><?php esc_html_form_error_msg_e('password', 'form_errors') ?></div>
        </div>
        <input type="submit" value="se connecter" class="btn-edition">
    </form>
</div>



<?php if (is_signup_activated()) : ?>
    <div class="sign-up">
        <p>Pas encore de compte ? <a href="sign-up">s'inscrire</a></p>
    </div>
<?php endif; ?>