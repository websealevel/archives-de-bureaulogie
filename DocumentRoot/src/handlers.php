<?php

/**
 * Gestion des Exceptions et des Erreurs de manière globale
 *
 * @package wsl 
 */

//Global Exception handler.
set_exception_handler(function ($e) {

    if ($e instanceof ErrorException) {
        $errorType = "Error";
    } else {
        $errorType = "Exception";
    }

    error_log("=> type: " . $errorType . " code : " . $e->getCode() . " message: " . $e->getMessage() . " trace: " . $e->getTraceAsString());
});

//Global Error handler.
set_error_handler(function ($errno, $errstr, $errfile, $errline) {

    //Convert errors included in our error-reporing setting into Exceptions
    if (!(error_reporting() & $errno)) {
        return;
    }
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});
