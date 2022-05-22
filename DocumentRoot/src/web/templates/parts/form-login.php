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

<div class="annonce">Les archives de bureaulogie sont en cours de construction... L'ouverture est prévue courant juin 2022</div>

<h2>se connecter</h2>
<div class="form-login">
    <form action="log-in" method="POST">
        <div>
            <label for="login">pseudo </label>
            <input type="text" id="login" name="login" value="paul">
            <div class="error-message"><?php esc_html_form_error_msg_e('login', 'form_errors') ?></div>
        </div>
        <div>
            <label for="password">pot de passe </label>
            <input type="password" id="password" name="password" value="password">
            <div class="error-message"><?php esc_html_form_error_msg_e('password', 'form_errors') ?></div>
        </div>
        <input type="submit" value="connexion">
    </form>
</div>



<?php if (is_signup_activated()) : ?>
    <div class="sign-up">
        <p>Pas encore de compte ? <a href="sign-up">S'inscrire</a></p>
    </div>
<?php endif; ?>