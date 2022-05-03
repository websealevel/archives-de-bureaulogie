<?php

/**
 * Toutes les fonctions qui manage les passwords
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

/**
 * Retourne un mot de pass hashé
 * @param string $password_to_hash Le mot de passe en clair
 * @return string Le mot de passe hashé
 * @throws Exception - Si le mot de passe a hashé est vide
 */
function my_hash_password(string $password_to_hash)
{

    if (empty($password_to_hash))
        throw new Exception("Hashage d'un mot de passe vide");

    $hashed_password = password_hash(
        $password_to_hash,
        PASSWORD_DEFAULT,
        ['cost' => 12]
    );


    if (false === $hashed_password)
        throw new Exception("Le hashage du mot de passe a échoué");

    return $hashed_password;
}
