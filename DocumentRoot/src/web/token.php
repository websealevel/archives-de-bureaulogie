<?php

/**
 * Fonctions qui gere les tokens pour consommer l'API Ajax de l'appli
 *
 * @link
 *
 * @package wsl 
 */


/**
 * Enregistre et retourne un token API pour le compte avec une durée de vie limitée. 
 * @param string $account L'id du compte pour qui est le token
 * @param int $expiration_in_s La durée de validité du token en secondes
 * @return string le token
 */
function register_api_token(string $account, int $expiration_in_s = 7200): string
{

    $factory = new RandomLib\Factory;
    $generator = $factory->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));


    $token = $generator->generateString(120);

    write_log($token);

    return 'foobar';
}
