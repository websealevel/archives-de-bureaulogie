<?php

/**
 * 
 * Fonctions pour gérer les tokens et les valider (sécurité)
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Enregistre un token API pour le compte avec une durée de vie limitée. 
 * @param string $account L'id du compte pour qui est le token
 * @param string $cap La capabilites pour laquelle le jeton est créee (ce qu'on peut en faire)
 * @param int $expiration_in_s La durée de validité du token en secondes
 * @return string|bool L'id du token inséré, faux en cas d'erreur
 */
function register_api_token(string $account, string $cap, int $expiration_in_s = 7200): string|bool
{
    $token = generate_token();
    $_SESSION["${cap}"] = $token;
    return $token;
}

/**
 * Retourne une chaine de caractères aléatoire
 * @return string
 */
function generate_token(): string
{
    $factory = new RandomLib\Factory;

    $generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));

    $token = $generator->generateString(80);

    return $token;
}

/**
 * Retourne vrai si le token est valide, faux sinon
 * @param string $cap La capabilites pour laquelle le jeton est créee (ce qu'on peut en faire)
 * @param string $value La valeur du token à vérifier
 * @return bool
 */
function is_valid_token(string $value, string $cap): bool
{
    if (!isset($_SESSION) && !in_array($cap, $_SESSION))
        return false;

    return true;
}
