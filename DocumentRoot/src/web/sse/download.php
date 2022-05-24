<?php

/**
 * Server Send Event (SSE) utilisé pour observer les téléchargements en cours
 * @link https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
 *
 * @package wsl 
 */

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
    echo 'data: {"content": false}';
    echo "\n\n";
    ob_end_flush();
    exit;
}

//Trouver les téléchargements en cours
$active_downloads = active_downloads();
$nb_downloads = count($active_downloads);

ob_start();
echo 'data: { content: true';
echo '"active_downloads" : [';
foreach ($active_downloads as $index => $download) {
    $progression_formated = str_replace('%', '', $download['progression']);
    echo sprintf('{"id": "%s", "url": "%s", "filename": "%s", "progression": "%s", "speed": "%s" }', $download['id'], $download['url'], $download['filename'], $progression_formated, $download['speed']);
    if ($index !== $nb_downloads)
        echo ',';
}
echo ']';
echo '}';
echo "\n\n";

$content = ob_get_contents();

//Valider le JSON
if (!is_valid_json($content)) {
    write_log('LE SSE renvoie un JSON Invalide');
    ob_end_clean();
    ob_start();
    echo 'data: {"content": false}';
    echo "\n\n";
    ob_end_flush();
    exit;
}
ob_end_flush();
exit;
