<?php

/**
 * Template form de création de compte
 * @link https://www.php.net/manual/en/function.session-start.php
 *
 * @package wsl 
 */
session_start();
autoload();
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
?>
<?php present_header(); ?>

<p>Fil d'ariane</p>

<?php esc_html_notices_e(); ?>

<h2>Formulaire d'inscription</h2>

<form action="sign-up" method="post">

    <div>
        <label for="pseudo">Pseudonyme</label>
        <input type="text" name="pseudo" value="<?php echo "paul";/*esc_html_from_session_e('form_errors', 'pseudo')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('pseudo', 'form_errors') ?></div>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo "contact@websealevel.com" /*esc_html_from_session_e('form_errors', 'email')*/ ?>" required>
        <div class="error-message"><?php esc_html_form_error_msg_e('email', 'form_errors') ?></div>
    </div>

    <div>
        <label for="password">Mot de passe</label>
        <input type="password" name="password" required minlength="6" maxlength="12" value="password">
        <div class="error-message"><?php echo  esc_html_form_error_msg_e('password', 'form_errors') ?></div>
    </div>


    <div>
        <label for="password_confirmation">Confirmer votre mot de passe</label>
        <input type="password" name="password_confirmation" required value="password">
        <div class="error-message"><?php esc_html_form_error_msg_e('password_confirmation', 'form_errors') ?></div>
    </div>

    <div>
        <label for="heard_about_bureaulogy">Comment avez-vous découvert la bureaulogie ?</label>
        <select name="heard_about_bureaulogy" id="">
            <option hidden disabled selected value> -- choisir une réponse -- </option>
            <option value="tribunal_des_bureaux">au tribunal</option>
            <option value="par_un_proche">oui, exactement</option>
            <option value="par_un_proche">pas tout à fait</option>
            <option value="unknow">je ne souhaite pas répondre</option>
            <option value="unknow"><i>je ne connais pas la bureaulogie</i></option>
        </select>
    </div>


    <ul class="sign-up--conditions">
        <li><input type="checkbox" name="charte" checked>
            <label for="condition_2" required>J'ai reconnais avoir lu et compris <a href="/charte">la charte de {XXX}</a></label>
        </li>
        <li> <input type="checkbox" name="majority" checked>
            <label for="majority" required>Je certifie être majeur</label>
        </li>

    </ul>

    <input type="submit" value="Créer mon compte">
</form>
<p>
<p>Par soucis écologique nous ne vous enverrons aucun mail, sauf pour vous tenir informé·e de vos éventuelles contributions.</p>
<p>Par mesure de sécurité, et en accord avec les principes de la bureaulogie, vous n'aurez pas la possibilité de recouvrir votre mot de passe en cas de perte. Conservez le soigneusement sur votre bureau.</p>
<a href="/politique-de-confidentialite">Que fait {XXX} de mes données personnelles ? </a>
</p>

<p>
    <a href="/">Retour à la page d'accueil</a>
</p>

<?php present_footer(); ?>