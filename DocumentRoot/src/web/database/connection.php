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
 * @return PgSql\Connection|bool 
 */
function connection_to_db(string $credentials = DB_CREDENTIALS)
{

    $db = pg_connect("$credentials");

    if (!$db) {
        throw new Error("Impossible de se connecter à la base de données.");
    }

    return $db;
}
