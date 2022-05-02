<?php

/**
 * Template form de création de compte
 * @link https://www.php.net/manual/en/function.session-start.php
 *
 * @package wsl 
 */
?>

<?php
autoload();
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../utils.php';
?>

<?php dump($_SESSION); ?>

<?php present_header(); ?>

<h2>Inscription au département de bureaulogie</h2>

<form action="sign-up" method="post">

    <div>
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" value="<?php esc_html_from_session_e('form_errors', 'pseudo') ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('pseudo', $_SESSION['form_errors']) ?></div>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php esc_html_from_session_e('form_errors', 'email') ?>"required>
        <div class="error-message"><?php esc_html_form_error_msg_e('email', $_SESSION['form_errors']) ?></div>
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" name="password"required>
        <div class="error-message"><?php esc_html_form_error_msg_e('password', $_SESSION['form_errors']) ?></div>
    </div>


    <div>
        <label for="password_confirmation">Confirmer votre mot de passe</label>
        <input type="password" name="password_confirmation" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('password_confirmation', $_SESSION['form_errors']) ?></div>
    </div>


    <div>
        <label for="level">Niveau</label>

        <select name="level" id="">
            <option selected value="student">Étudiant·e en bureaulogie</option>
            <option value="other">Autre</option>
            <option disabled value="master">Maître bureaulogue</option>
        </select>
        <div class="error-message"><?php esc_html_form_error_msg_e('level', $_SESSION['form_errors']) ?></div>
    </div>

    <input type="submit" value="Créer mon compte">
</form>


<?php present_footer(); ?>