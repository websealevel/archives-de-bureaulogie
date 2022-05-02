<?php

/**
 * Renvoie une connexion à la base de donnée
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . 'credentials.php';

function connection_to_db(string $host, string $port, string $dbname, string $credentials)
{

    $db = pg_connect("$host $port $dbname $credentials");

    if (!$db) {
        throw new Error("Impossible de se connecter à la base de données.");
    }

    return $db;
}
