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

<h2>Formulaire d'inscription à l'<span>ULB</span></h2>

<h3>Conditions d'inscription</h3>

<p>Les frais d'inscription sont entièrement pris en charge par la Sécurité Sociale. </p>

<?php esc_html_notices_e(); ?>

<form action="sign-up" method="post">

    <div>
        <label for="pseudo">Pseudonyme</label>
        <input type="text" name="pseudo" value="<?php echo "foo";/*esc_html_from_session_e('form_errors', 'pseudo')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('pseudo', 'form_errors') ?></div>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo "foo@bar.com" /*esc_html_from_session_e('form_errors', 'email')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('email', 'form_errors') ?></div>
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" required minlength="6" maxlength="12" value="123456">
        <div class="error-message"><?php echo  esc_html_form_error_msg_e('password', 'form_errors') ?></div>
    </div>


    <div>
        <label for="password_confirmation">Confirmer votre mot de passe</label>
        <input type="password" name="password_confirmation" required value="123456">
        <div class="error-message"><?php esc_html_form_error_msg_e('password_confirmation', 'form_errors') ?></div>
    </div>


    <div>
        <label for="level">Niveau</label>

        <select name="level" id="">
            <option selected value="studentL1">Étudiant·e en 1ère année de bureaulogie </option>
            <option selected value="studentL2">Étudiant·e en 2ème année de bureaulogie </option>
            <option selected value="studentL3">Étudiant·e en 3ème année de bureaulogie </option>
            <option value="other">Inscription Libre</option>
        </select>
        <div class="error-message"><?php esc_html_form_error_msg_e('level', 'form_errors') ?></div>
    </div>

    <div>
        <label for="speciality">Spécialité</label>

        <select name="speciality" id="">
            <option selected value="cable_managment"></option>
            <option value="peripheriques_obseletes">Périphériques obsolètes [Dpt Épistémologie]</option>
            <option value="perroquet">Perroquet</option>
        </select>
        <div class="error-message"><?php esc_html_form_error_msg_e('level', 'form_errors') ?></div>
    </div>

    <div>
        <input type="checkbox" name="condition_1" checked >
        <label for="condition_1" required>J'accepte les conditions etc... </label>
        <input type="checkbox" name="condition_2" checked>
        <label for="condition_2" required>J'ai pris connaissance de la charte de l'Université Libre de Bureaulogie </label>
    </div>

    <input type="submit" value="Créer mon compte">
</form>
<p>
<a href="/politique-de-confidentialite">Comment l'Université Libre de Bureaulogie utilisent mes données personnelles ? </a>
</p>

<p>
    <a href="/">Retour à la page d'accueil</a>
</p>

<?php present_footer(); ?>