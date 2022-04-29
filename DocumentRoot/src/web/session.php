<?php

/**
 * Toutes les fonctions manipulant la session php
 *
 * @package wsl 
 */


/**
 * Retourne la valeur sous la clef $key de la session en cours, une chaine vide sinon
 * @param string $key La clef demandée
 * @param string $array_key Optional Default = ''. Si la valeur demandée est un tableau, renvoie la valeur sous la clef $array_key
 * @return mixed La valeur sous la clef
 */
function from_session(string $key, string $array_key = '')
{
    if (!isset($_SESSION))
        return '';

    $value = $_SESSION["{$key}"] ?? '';

    if (is_array($value)) {
        if (!empty($array_key)) {
            return $value["{$array_key}"];
        } else {
            return $value;
        }
    }

    return $value;
}
