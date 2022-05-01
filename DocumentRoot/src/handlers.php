<?php

/**
 * Gestion des Exceptions et des erreurs de manière globale
 *
 * @package wsl 
 */

//Global Exception handler
set_exception_handler(function ($e) {

    if ($e instanceof ErrorException) {
        $errorType = "Error";
    } else {
        $errorType = "Exception";
    }

    error_log("=> type: " . $errorType . " code : " . $e->getCode() . " message: " . $e->getMessage() . " trace: " . $e->getTraceAsString());
});
//Global Error handler
ini_set('error_log', 'journal.log');
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    //Convert errors included inuour error-reporing setting into Exceptions
    if (!(error_reporting() & $errno)) {
        die;
    }
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});
