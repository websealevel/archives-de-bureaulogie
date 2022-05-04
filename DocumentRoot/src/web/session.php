<?php

/**
 * Toutes les fonctions manipulant la session php
 *
 * @package wsl 
 */

require_once __DIR__ . '/../models/InputValidation.php';

/**
 * Retourne la valeur sous la clef $key de la session en cours, une chaine vide sinon
 * A refactor (dégueulasse).
 * @param string $key La clef demandée
 * @param string $array_key Optional. Default = ''. Si la clé demandée référence un tableau en session, retourne la valeur sous la clef $array_key du tableau
 * @return mixed La valeur sous la clef
 * @global array $_SESSION
 * @throws Exception Si aucune session n'est ouverte
 */
function from_session(string $key, string $array_key = '')
{
    global $_SESSION;

    if (!isset($_SESSION)) {
        throw new Exception("@from_session: la session n'est pas ouverte");
    }

    $value = $_SESSION["{$key}"] ?? '';

    if (empty($value))
        return $value;

    if (is_array($value)) {
        if (!empty($array_key)) {
            if (!isset($value["{$array_key}"])) {
                throw new Exception("@from_session: la clé demandée n'existe pas");
            }
            $array_value = $value["{$array_key}"];
            return retrieve_value($array_value);
        } else {
            //On utilise le tableau comme valeur
            return retrieve_value($value);
        }
    }

    return retrieve_value($value);
}


/**
 * Ecrit et échappe sur la sortie standard une valeur enregistrée dans la session
 * @param string $key La clef demandée
 * @param string $array_key Optional Default = ''. Si la valeur demandée est un tableau, renvoie la valeur sous la clef $array_key
 * @return void
 */
function esc_html_from_session_e(string $key, string $array_key): void
{
    $session_value = from_session($key, $array_key);
    esc_html_e($session_value);
}

/**
 * Ecrit sur la sortie standard le message d'erreur associé à l'input du form s'il existe, rien sinon
 * @param string $input_name Le nom du champ du formuliare
 * @param InputValidation[] $form_errors
 * @throws Exception - Si aucune session n'est ouverte
 * @return void
 */
function esc_html_form_error_msg_e(string $input_name, string $key_form_errors)
{

    if (!isset($_SESSION) || !isset($_SESSION["{$key_form_errors}"]))
        return;

    $form_errors = $_SESSION["{$key_form_errors}"];

    if (!array_key_exists($input_name, $form_errors))
        return;

    esc_html_e($form_errors["{$input_name}"]->message);
}

/**
 * Ecrit sur la sortie standard les notices présentes en session
 * @global $_SESSION
 * @throws Exception - Si aucune session n'est ouverte
 */
function esc_html_notices_e()
{
    if (!isset($_SESSION) || !isset($_SESSION['notices']))
        return;

    $notices = $_SESSION['notices'];

    if (!is_array($notices))
        throw new Exception("@esc_html_notices_e: les notices ne sont pas empilées dans un tableau");

    $html_notices = array_map(function (Notice $notice) {
        return '<li class="notice ' . $notice->status->value . '">' . $notice->message . '</li>';
    }, $notices);

    echo '<ul class="notices">';
    foreach ($html_notices as $html_notice) {
        echo $html_notice;
    }
    echo '</ul>';

    unset($_SESSION['notices']);
}

/**
 * Enregistre le compte utilisateur en session
 * @param mixed $account Les données du compte utilisateur
 * @global array $_SESSION
 * @return void
 */
function login_user_session($account)
{

    if (!isset($_SESSION))
        throw new Exception("Aucune session n'est ouverte");

    $_SESSION['user_authentificated'] = true;
    $_SESSION['pseudo'] = $account->pseudo;


    error_log_login_success($account);
}

