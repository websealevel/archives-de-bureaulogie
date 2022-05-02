<?php

/**
 * Wrap données user
 *
 * @package wsl 
 */

class User
{
    public function __construct(

        public  string $pseudo,

        public string $hash_password,

        public string $email,

        public string $level

    ) {
    }
}
