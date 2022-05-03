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

    $env_path = SRC_PATH;

    $dotenv = Dotenv\Dotenv::createImmutable($env_path, '.env_db');
    $dotenv->load();

    // $db = pg_connect("$credentials");
    dump(DB_DSN);
    // die;
    // try {
    //     $db = new PDO($dsn, $user, $password);
    // } catch (PDOException $e) {
    //     error_log($e);
    //     exit;
    // }

    // return $db;
}
