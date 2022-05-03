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
require_once __DIR__ . '/../university.php';
?>

<?php present_header(); ?>

<p>Fil d'ariane</p>

<h2>S'inscrire à l'<span>ULB</span></h2>

<?php esc_html_notices_e(); ?>

<h2>Formulaire d'inscription</h2>

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
            <option value="other">Candidat Libre</option>
        </select>
        <div class="error-message"><?php esc_html_form_error_msg_e('level', 'form_errors') ?></div>
    </div>

    <div>
        <?php esc_html_select_majors_e(university_majors()); ?>
        <div class="error-message"><?php esc_html_form_error_msg_e('major', 'form_errors') ?></div>
    </div>

    <ul class="sign-up--conditions">
        <li><input type="checkbox" name="charte" checked>
            <label for="condition_2" required>J'ai reconnais avoir lu et compris le sens et les implications de <a href="/charte">la charte éthique et informatique de l'Université Libre de Bureaulogie</a></label>
        </li>
        <li> <input type="checkbox" name="majority" checked>
            <label for="majority" required>Je certifie être majeur</label>
        </li>

    </ul>

    <input type="submit" value="Créer mon compte">
</form>
<p>
    <a href="/politique-de-confidentialite">Que fait l'Université Libre de Bureaulogie de mes données personnelles ? </a>
</p>

<p>
    <a href="/">Retour à la page d'accueil</a>
</p>

<?php present_footer(); ?>