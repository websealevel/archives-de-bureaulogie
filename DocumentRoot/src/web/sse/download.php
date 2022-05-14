<?php
/**
 * Server Send Event (SSE) utilisé pour observer les téléchargements en cours
 * @link https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../current-user.php';
require_once __DIR__ . '/../database/repository-downloads.php';



header("Cache-Control: no-store");
header("Content-Type: text/event-stream");

//Authentification
session_id($_REQUEST['PHPSESSID']);
session_start();

//Check les droits
if (!current_user_can('add_source')) {
    ob_start();
    $foo = 'Refusé';
    echo 'data: {"access": "Refusé"}';
    echo "\n\n";
    ob_end_flush();
    exit;
}

//Trouver les téléchargements en cours
$active_downloads = active_downloads(from_session('account_id'));

//Envoyer les infos de progression
$progression = 0;

ob_start();
echo 'data: {';
echo '"pending_downloads" : [';
foreach ($active_downloads as $download) {
    echo sprintf('{"id" : "%s", "url": "%s", "filename" : "%s", "progression": "%s" }', $download['id'], $download['url'], $download['filename'], $progression);
}
echo ']';
echo '}';

echo "\n\n";
ob_end_flush();
exit;