<?php

/**
 * 
 * Fonctions pour gérer les tokens et les valider (sécurité)
 *
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/queries-token.php';

/**
 * Enregistre un token API pour le compte avec une durée de vie limitée. 
 * @param string $account L'id du compte pour qui est le token
 * @param int $expiration_in_s La durée de validité du token en secondes
 * @return string|bool L'id du token inséré, faux en cas d'erreur
 */
function register_api_token(string $account, int $expiration_in_s = 7200): string|bool
{

    $factory = new RandomLib\Factory;

    $generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));

    $token = $generator->generateString(120);

    try {
        $token_id = sql_insert_token($token, $account, $expiration_in_s);
    } catch (Exception $e) {
    }

    return $token_id;
}

/**
 * Retourne vrai si le token est valide, faux sinon
 */
function is_valid_token(string $value)
{
}
