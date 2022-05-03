<?php

/**
 * Renvoie une connexion à la base de donnée
 * @link
 *
 * @package wsl 
 */

autoload();

/**
 * Retourne une connexion à la base de données en cas de succès, faux sinon
 * @param $string $credentials La chaine de caractères contenant les credentials postgresql
 * @return 
 */
function connection_to_db()
{

    $credentials = load_db_env();

    $dsn = dsn_from_credentials($credentials);

    dd($dsn);

    try {
        $db = new PDO($dsn, $credentials['user'], $credentials['password']);

        dd($db);
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
function load_db_env(string $env_path = SRC_PATH, string $env_file = '.env_db'): array
{

    if (isset($_ENV['db_env']))
        return $_ENV['db_env'];

    $dotenv = Dotenv\Dotenv::createImmutable($env_path, $env_file);
    $dotenv->load();

    $credentials = array(
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD']
    );

    //On concatène tout ça.
    $_ENV['db_env'] = $credentials;

    return $credentials;
}


function dsn_from_credentials(array $credentials): string
{
    return sprintf(
        "host:%s;dbname:%s;port:%s;charset=utf8",
        $credentials['host'],
        $credentials['dbname'],
        $credentials['port']
    );
}
