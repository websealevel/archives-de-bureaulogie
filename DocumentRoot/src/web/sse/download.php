<?php

/**
 * Server send event utilisé pour lancer le téléchargement d'une vidéo source
 * @link https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
 *
 * @package wsl 
 */

require_once __DIR__ . '/../current-user.php';

header("Cache-Control: no-store");
header("Content-Type: text/event-stream");


//Check les droits

// session_id($_REQUEST['PHPSESSID']);
session_id('toto');
session_start();
write_log($_SESSION);

if (true) {
    // if (!current_user_can('add_source')) {
    echo 'data: {"Autorisation" : "refusée"}';
    echo "\n\n";
    exit;
}

while (true) {
    // Every 3 second, send a "ping" event.
    $foo = 'ping';
    echo 'data: {"ping": "' . $foo . '"}';
    echo "\n\n";

    while (ob_get_level() > 0) {
        ob_end_flush();
    }
    flush(); //Send to the browser

    //On the server

    // Break the loop if the client aborted the connection (closed the page)
    if (connection_aborted()) break;

    sleep(3);
}
