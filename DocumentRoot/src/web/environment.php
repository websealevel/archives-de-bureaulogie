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

    load_default_values();
}

/**
 * Charge des variables d'environnement par défaut si elles ne sont pas trouvées dans le .env
 * @return void
 */
function load_default_values(): void
{
    if (!isset($_ENV['SITE_MAINTENANCE_MODE']))
        $_ENV['SITE_MAINTENANCE_MODE'] = 1;

    if (!isset($_ENV['SITE_DISABLE_SIGN_UP']))
        $_ENV['SITE_DISABLE_SIGN_UP'] = 1;

    if (!isset($_ENV['PATH_BIN_FFMPEG']))
        $_ENV['PATH_BIN_FFMPEG'] = dirname(__DIR__) . '/' . 'ffmpeg/ffmpeg';

    if (!isset($_ENV['PATH_BIN_FFPROBE']))
        $_ENV['PATH_BIN_FFPROBE'] = dirname(__DIR__) . '/' . 'ffmpeg/ffprobe';

    if (!isset($_ENV['PATH_PYTHON']))
        $_ENV['PATH_PYTHON'] = '/usr/bin/python3';

    if (!isset($_ENV['PATH_BIN_YOUTUBEDL']))
        $_ENV['PATH_BIN_YOUTUBEDL'] = dirname(__DIR__) . '/' . 'youtube-dl/youtube-dl';

    dd($_ENV);
    return;
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
