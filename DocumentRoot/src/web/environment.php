<?php

/**
 * Gestion des variables d'environnement
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/utils.php';

autoload();

/**
 * Chare les variables d'environnement dans la global $_ENV
 * @global array $_ENV
 */
function load_env(string $env_path = SRC_PATH, string $env_file = '.env')
{
    $dotenv = Dotenv\Dotenv::createImmutable($env_path, $env_file);
    try {
        $dotenv->load();
    } catch (Dotenv\Exception\InvalidPathException $e) {
        error_log($e);
        throw new Exception("Impossible de charger les variables d'environnement");
    }
}

/**
 * Retourne vrai si le site est en maintenance, faux sinon
 * @global array $_ENV
 */
function in_maintenance_mode(): bool
{
    if (isset($_ENV['SITE_MAINTENANCE_MODE']))
        return boolval($_ENV['SITE_MAINTENANCE_MODE']);

    return true;
}

/**
 * Retourne vrai si la création de compte est activée, faux sinon
 * @global array $_ENV
 */
function is_signup_activated(): bool
{
    if (isset($_ENV['SITE_DISABLE_SIGN_UP']))
        return boolval($_ENV['SITE_DISABLE_SIGN_UP']);

    return true;
}
