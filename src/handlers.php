<?php

/**
 * Gestion des Exceptions et des erreurs de manière globale
 *
 * @package wsl 
 */

//Global Exception handler
set_exception_handler(function ($e) {
    echo 'Oups, il y a eu un problème :/' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    error_log($e);
    die;
});
//Global Error handler
ini_set('error_log', 'journal.log');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    //Convert errors included inuour error-reporing setting into Exceptions
    if (!(error_reporting() & $errno)) {
        return;
    }

    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});
