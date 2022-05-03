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

<?php present_header(); ?>

<h2>Devenir membre de la FDB</h2>

<form action="sign-up" method="post">

    <div>
        <label for="pseudo">Pseudonyme</label>
        <input type="text" name="pseudo" value="<?php esc_html_from_session_e('form_errors', 'pseudo') ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('pseudo', 'form_errors') ?></div>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php esc_html_from_session_e('form_errors', 'email') ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('email', 'form_errors') ?></div>
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" required minlength="6" maxlength="12">
        <div class="error-message"><?php esc_html_form_error_msg_e('password', 'form_errors') ?></div>
    </div>


    <div>
        <label for="password_confirmation">Confirmer votre mot de passe</label>
        <input type="password" name="password_confirmation" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('password_confirmation', 'form_errors') ?></div>
    </div>


    <div>
        <label for="level">Niveau</label>

        <select name="level" id="">
            <option selected value="student">Étudiant·e en bureaulogie</option>
            <option value="other">Autre</option>
            <option disabled value="master">Maître bureaulogue</option>
        </select>
        <div class="error-message"><?php esc_html_form_error_msg_e('level', 'form_errors') ?></div>
    </div>

    <div>
        <input type="checkbox" name="condition_1">
        <label for="condition_1" required>J'accepte les conditions etc... </label>
        <input type="checkbox" name="condition_2">
        <label for="condition_2" required>J'ai pris connaissance de la charte de la fondation libre de bureaulogie </label>
    </div>

    <input type="submit" value="Créer mon compte">
</form>


<a href="/">Retour à la page d'accueil</a>

<?php present_footer(); ?>