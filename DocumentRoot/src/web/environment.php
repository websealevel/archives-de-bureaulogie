<?php

/**
 * Gestion des variables d'environnement
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../path.php';
require_once __DIR__ . '/utils.php';

autoload();

/**
 * Chare les variables d'environnement dans la global $_ENV
 * @global array $_ENV
 */
function load_env(string $env_path = SRC_PATH, string $env_file = '.env')
{

    if (isset($_ENV['env_archives_de_bureaulogie.fr']))
        return;

    $dotenv = Dotenv\Dotenv::createImmutable($env_path, $env_file);
    try {
        $dotenv->load();
        $_ENV['env_archives_de_bureaulogie.fr'] = true;
    } catch (Dotenv\Exception\InvalidPathException $e) {
        error_log($e);
        throw new Exception("Impossible de charger les variables d'environnement");
    }

    load_default_values();
}


/**
 * Retourne la valeur sous la clef $key dans la variable d'environnement $_ENV
 * @param string $key La clé sous la quelle est enregistrée la valeur recherchée dans $_ENV
 * @global array $_ENV
 * @return string La valeur demandée, une chaine vide sinon
 */
function from_env(string $key): string
{
    return $_ENV["$key"] ?? '';
}

/**
 * Charge des variables d'environnement par défaut si elles ne sont pas trouvées dans le .env
 * @return void
 */
function load_default_values(): void
{
    if (!isset($_ENV['SITE_MAINTENANCE_MODE'])) {
        $_ENV['SITE_MAINTENANCE_MODE'] = 1;
    }

    if (!isset($_ENV['SITE_DISABLE_SIGN_UP']))
        $_ENV['SITE_DISABLE_SIGN_UP'] = 1;

    if (!isset($_ENV['PATH_BIN_FFMPEG']))
        $_ENV['PATH_BIN_FFMPEG'] = dirname(__DIR__, 2) . '/' . 'ffmpeg/ffmpeg';
    else
        $_ENV['PATH_BIN_FFMPEG'] = dirname(__DIR__, 2) . '/' . $_ENV['PATH_BIN_FFMPEG'];

    if (!isset($_ENV['PATH_BIN_FFPROBE']))
        $_ENV['PATH_BIN_FFPROBE'] = dirname(__DIR__, 2) . '/' . 'ffmpeg/ffprobe';
    else
        $_ENV['PATH_BIN_FFPROBE'] = dirname(__DIR__, 2) . '/' . $_ENV['PATH_BIN_FFPROBE'];

    if (!isset($_ENV['PATH_PYTHON']))
        $_ENV['PATH_PYTHON'] = '/usr/bin/python3';

    if (!isset($_ENV['PATH_BIN_YOUTUBEDL']))
        $_ENV['PATH_BIN_YOUTUBEDL'] = dirname(__DIR__, 2) . '/' . 'youtube-dl/youtube-dl';
    else
        $_ENV['PATH_BIN_YOUTUBEDL'] = dirname(__DIR__, 2) . '/' . $_ENV['PATH_BIN_YOUTUBEDL'];

    if (!isset($_ENV['FFMPEG_THREADS']))
        $_ENV['FFMPEG_THREADS'] = 2;

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
        return !boolval($_ENV['SITE_DISABLE_SIGN_UP']);

    return true;
}
