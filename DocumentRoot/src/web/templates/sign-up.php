<?php

/**
 * Template form de création de compte
 * @link https://www.php.net/manual/en/function.session-start.php
 *
 * @package wsl 
 */
autoload();
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';

if (!is_signup_activated())
    redirect('/');

?>
<?php present_header(); ?>
<h2>Formulaire d'inscription</h2>

<form action="sign-up" method="post">
    <div class="form-note">Les champs marqués d'un asterisque sont obligatoires</div>

    <div>
        <label for="pseudo">Pseudo <span class="required">*</span></label>
        <input type="text" name="pseudo" value="<?php echo "paul";/*esc_html_from_session_e('form_errors', 'pseudo')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('pseudo', 'form_errors') ?></div>
    </div>

    <div>
        <label for="email">Email <span class="required">*</span></label>
        <input type="email" name="email" value="<?php echo "contact@websealevel.com" /*esc_html_from_session_e('form_errors', 'email')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('email', 'form_errors') ?></div>
    </div>

    <div>
        <label for="password">Mot de passe <span class="required">*</span></label>
        <input type="password" name="password" required minlength="6" maxlength="12" value="password">
        <div class="error-message"><?php echo  esc_html_form_error_msg_e('password', 'form_errors') ?></div>
    </div>


    <div>
        <label for="password_confirmation">Confirmer votre mot de passe <span class="required">*</span></label>
        <input type="password" name="password_confirmation" required value="password">
        <div class="error-message"><?php esc_html_form_error_msg_e('password_confirmation', 'form_errors') ?></div>
    </div>

    <div>
        <label for="heard_about_bureaulogy">Comment avez-vous découvert la bureaulogie ?</label>
        <select name="heard_about_bureaulogy" id="">
            <option hidden disabled selected value> -- choisir une réponse -- </option>
            <option value="tribunal_des_bureaux">au tribunal</option>
            <option value="yes">oui, exactement</option>
            <option value="not_really">pas tout à fait</option>
            <option value="monty_python">je suis fan des Monty Python</option>
            <option value="unknow">je ne souhaite pas répondre</option>
            <option value="unknow"><i>je ne connais pas la bureaulogie</i></option>
        </select>
    </div>


    <ul class="sign-up--conditions">
        <li>
            <?php esc_html_form_error_msg_e('charte', 'form_errors') ?>
            <input type="checkbox" id="id_charte" name="charte">
            <label for="id_charte" required>Je reconnais avoir lu et compris <a href="/charte">la charte des Archives de Bureaulogie</a>, et je m'engage à la respecter <span class="required">*</span></label>
        </li>
        <li>
            <?php esc_html_form_error_msg_e('majority', 'form_errors') ?>
            <input type="checkbox" id="id_majority" name="majority">
            <label for="id_majority" required>Je certifie être majeur·e<span class="required">*</span></label>
        </li>

    </ul>

    <input type="submit" value="Créer mon compte">
</form>
<p>

<p>Par soucis écologique nous ne vous enverrons aucun email inutile, sauf pour valider votre compte et vous tenir informé·e de vos futures contributions.</p>

<p>Par mesure de sécurité, et en accord avec les principes de la bureaulogie, vous n'aurez pas la possibilité de recouvrir votre mot de passe en cas de perte. Conservez le soigneusement sur votre bureau.</p>
<a href="/confidentiality-policy">Que font les Archives de Bureaulogie de mes données personnelles ? </a>
</p>

<p class="contact"> <a href="/contact">Nous contacter</a> </p>


<?php present_footer(); ?>