<?php

/**
 * Toutes les fonctions manipulant la session php
 *
 * @package wsl 
 */


/**
 * Retourne la valeur sous la clef $key de la session en cours, une chaine vide sinon
 * @param string $key La clef demandée
 * @param string $array_key Optional. Default = ''. Si la clé demandée référence un tableau en session, retourne la valeur sous la clef $array_key du tableau
 * @return mixed La valeur sous la clef
 */
function from_session(string $key, string $array_key = '')
{
    if (!isset($_SESSION))
        return '';

    $value = $_SESSION["{$key}"] ?? '';

    if (empty($value))
        return $value;

    if (is_array($value)) {
        if (!empty($array_key)) {
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
    $session_value = from_session('form_errors', 'pseudo');
    esc_html_e($session_value);
}
