<?php

/**
 * Renvoie une connexion à la base de donnée
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/access.php';

/**
 * Retourne une connexion à la base de données en cas de succès, faux sinon
 * @param $string $credentials La chaine de caractères contenant les credentials postgresql
 * @return 
 */
function connection_to_db(string $dsn = DB_DSN, string $user = DB_USER, string $password = DB_PASSWORD)
{

    // $db = pg_connect("$credentials");
    dump(DB_DSN);
    die;
    try {
        $db = new PDO($dsn, $user, $password);
    } catch (PDOException $e) {
        error_log($e);
        exit;
    }

    return $db;
}
