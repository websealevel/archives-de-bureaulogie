<?php

/**
 * Renvoie une connexion à la base de donnée
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/../environment.php';

/**
 * Retourne une connexion à la base de données en cas de succès, faux sinon
 * @param $string $credentials La chaine de caractères contenant les credentials postgresql
 * @return 
 */
function connect_to_db()
{

    $credentials = load_db_env();

    $dsn = dsn_from_credentials($credentials);

    try {
        $db = new PDO($dsn, $credentials['user'], $credentials['password']);
    } catch (PDOException $e) {
        error_log($e);
        exit;
    }

    return $db;
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
 * Charge les données définies dans le fichier d'environnement et les stocke dans $_ENV
 * @param string $env_path Le path du fichier d'environnement.
 * @param string $env_file Le nom du fichier.
 * @global array $_ENV
 * @return array
 */
function load_db_env(string $env_path = SRC_PATH, string $env_file = '.env'): array
{

    if (!isset($_ENV['DB_ENV_LOADED'])) {
        load_env();
        $_ENV['DB_ENV_LOADED'] = true;
    }

    $credentials = array(
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    );

    return $credentials;
}

/**
 * Retourne le DSN pour l'interface PDO
 * @param array $credentials Les credentials de la bdd
 * @return string Le dsn
 */
function dsn_from_credentials(array $credentials): string
{
    return sprintf(
        "pgsql:host=%s;dbname=%s;port=%s",
        $credentials['host'],
        $credentials['dbname'],
        $credentials['port']
    );
}
