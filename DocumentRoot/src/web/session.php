<?php

/**
 * Toutes les fonctions manipulant la session php et 
 * la super globale $_SESSION.
 *
 * @package wsl 
 */

require_once __DIR__ . '/../models/InputValidation.php';

/**
 * Retourne la valeur sous la clef $key de la session en cours, une chaine vide sinon
 * @see == A refactor (dégueulasse) ==
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
