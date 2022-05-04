<?php

/**
 * Un wrap de credentials pour le login
 *
 * @link
 * @package wsl 
 */


class Credentials
{
    public function __construct(

        public string $login,
        public string $password,
    ) {
    }
}
