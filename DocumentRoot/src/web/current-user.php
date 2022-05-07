<?php

/**
 * Toutes les fonctions checkant et gérant l'état de l'user courant ainsi que ses droits
 *
 * @package wsl 
 */


require_once __DIR__ . '/database/repository-accounts.php';
require_once __DIR__ . '/database/repository-roles-capabilities.php';

/**
 * Retourne vrai si l'utilisateur courant est connecté, faux sinon
 * @return bool
 */
function is_current_user_logged_in(): bool
{
    if (!isset($_SESSION))
        throw new Exception("Aucune session active");

    return boolval($_SESSION['user_authentificated'] ?? false);
}

/**
 * Retourne vrai si l'utilisateur authentifié a la capacité de faire cette action, faux sinon
 * @param string $cap La capacité à tester
 * @return bool
 * @throws Exception - Si la capacité n'existe pas
 */
function current_user_can(string $cap): bool
{

    //Check que la cap existe
    if (!cap_exists($cap)) {
        throw new Exception("La capacité " . $cap . "n'existe pas.");
    }

    //Récupere le compte et son role

    dd('Le role existe, le compte maintenant');

    //Check si une 2eme authentification est demandée (sécuritée renforcée sur certaine actions). Si c'est le cas redirect vers un petit écran confirmation du mot de passe.

    //Check si le role a la cap

    return false;
}
