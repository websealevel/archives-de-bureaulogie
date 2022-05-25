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
    echo 'data: {"content": false, "message" : "Vous n\' avez pas le droit d\'accéder à cette ressource."}';
    echo "\n\n";
    ob_end_flush();
    exit;
}

//Trouver les téléchargements en cours
$active_downloads = active_downloads();
$nb_downloads = count($active_downloads);

$json = '{ "content": true, "message" : "Informations sur les téléchargements en cours",';
$json .= '"active_downloads" : [';
foreach ($active_downloads as $index => $download) {

    $progression_formated = str_replace('%', '', $download['progression']);

    $json .= sprintf('{"id": "%s", "url": "%s", "filename": "%s", "progression": "%s", "speed": "%s" }', $download['id'], $download['url'], $download['filename'], $progression_formated, $download['speed']);

    if ($index + 1 !== $nb_downloads)
        $json .= ',';
}
$json .= ']}';

//Valider le JSON
if (!is_valid_json($json)) {
    ob_start();
    echo 'data: {"content": false, "message" : "Les données renvoyées par le serveur ont un format JSON invalide."}';
    echo "\n\n";
    ob_end_flush();
    exit;
}


ob_start();
//Le json est valide, on l'envoie au client
// echo 'data: ' . $data;
echo 'data: ' . $json;
echo "\n\n";
ob_end_flush();
flush();
