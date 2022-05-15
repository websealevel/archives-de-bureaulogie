<?php

/**
 * Structure de données contenant les credentials pour ouvrir une connexion à la base de données (DSN)
 * @link
 *
 * @package wsl 
 */

/**
 * Classe servant à stocker lles credentials pour ouvrir une connexion à la base de données (DSN)
 * @see 
 */
class CredentialsDB
{

    public function __construct(

        /** @var string L'host du sgbd*/
        public string $host = "",

        /** @var string Le nom de la db*/
        public string $dbname = "",

        /** @var string Le port utilisé par le sgbd*/
        public string $port = "",

        /** @var string L'user de la bd*/
        public string $user = "",

        /** @var string Le password de l'user de la bd*/
        public string $password = "",
    ) {
    }
}
