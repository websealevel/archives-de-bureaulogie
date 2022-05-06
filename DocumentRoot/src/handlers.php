<?php

/**
 * Gestion des Exceptions et des Erreurs de manière globale
 *
 * @package wsl 
 */


require_once __DIR__ . '/web/actions/log-out.php';
require_once __DIR__ . '/web/router/router.php';


//Global Exception handler.
set_exception_handler(function ($e) {

    if ($e instanceof ErrorException) {
        $errorType = "Error";
    } else {
        $errorType = "Exception";
    }

    error_log("=> type: " . $errorType . " code : " . $e->getCode() . " message: " . $e->getMessage() . " trace: " . $e->getTraceAsString());

    fails_gracefully();
});

//Global Error handler.
set_error_handler(function ($errno, $errstr, $errfile, $errline) {

    //Convert errors included in our error-reporing setting into Exceptions
    if (!(error_reporting() & $errno)) {
        return;
    }
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
});

/**
 * Deconnecte l'utilisateur en session, redirige vers la page d'accueil.
 * @return void
 * @global $_SESSION
 */
function fails_gracefully()
{
    //Fails gracefully : 
    $notice = new Notice("Oups, une erreur est survenue et vous avez été déconnecté. Veuillez nous en excusez.", NoticeStatus::ExceptionThrown);

    if (!isset($_SESSION)) {
        log_out($notice);
    } else {
        redirect('/', 'notices', array(
            $notice
        ));
    }
}
