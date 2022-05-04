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

        public string $password,

        public string $email,

    ) {
    }
}
