<?php

/**
 * Server send event utilisé pour lancer le téléchargement d'une vidéo source
 * @link https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
 *
 * @package wsl 
 */

header("Cache-Control: no-store");
header("Content-Type: text/event-stream");


//Check les droits



while (true) {
    // Every 3 second, send a "ping" event.
    $foo = 'ping';
    echo 'data: {"ping": "' . $foo . '"}';
    echo "\n\n";

    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush();
    //Log on the server
    error_log('message sent');

    // Break the loop if the client aborted the connection (closed the page)
    if (connection_aborted()) break;

    sleep(3);
}
