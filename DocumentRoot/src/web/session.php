<?php

/**
 * Toutes les fonctions manipulant la session php
 *
 * @package wsl 
 */

/**
 * Démarre une session php avec des options
 * @return bool Vrai si la session a démaré, faux sinon
 */
function start_session(): bool
{
    $options = array(
        'cookie_lifetime' => 3600,
    );

    return session_start($options);
}


/**
 * Retourne la valeur sous la clef $key de la session en cours, une chaine vide sinon
 * @param string $key La clef demandée
 * @param string $array_key Optional. Default = ''. Si la clé demandée référence un tableau en session, retourne la valeur sous la clef $array_key du tableau
 * @return mixed La valeur sous la clef
 * @global array $_SESSION
 */
function from_session(string $key, string $array_key = '')
{
    global $_SESSION;

    if (!isset($_SESSION)) {
        throw new Exception("from_session, la session n'est pas ouverte.");
    }
    
    $value = $_SESSION["{$key}"] ?? '';

    if (empty($value))
        return $value;

    if (is_array($value)) {
        if (!empty($array_key)) {

            if (!isset($value["{$array_key}"])) {
                throw new Exception("from_session, la clé demandée dans le tableau n'existe pas");
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
