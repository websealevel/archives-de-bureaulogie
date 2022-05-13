<?php

/**
 * Server send event utilisé pour lancer le téléchargement d'une vidéo source
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
    echo 'data: {"access": "Refusé"}';
    echo "\n\n";
    ob_end_flush();
    exit;
}

//Trouver les téléchargements pending associés au compte
$pending_downloads = pending_downloads(from_session('account_id'));
write_log($pending_downloads);

$progression = 0;

//Lancer les téléchargement et écrire la progression sur la sortie standard
ob_start();
echo 'data: {';
echo '"pending_downloads" : [';
foreach ($pending_downloads as $download) {
    echo sprintf('{"id" : "%s", "url": "%s", "filename" : "%s", "progression": "%s" }', $download['id'], $download['url'], $download['filename'], $progression);
}
echo ']';
echo '}';

echo "\n\n";
ob_end_flush();
exit;


// //Téléchargement.
// $yt = new YoutubeDl();

// //Show progress
// $yt->onProgress(static function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime): void {
//     echo "Download file: $progressTarget; Percentage: $percentage; Size: $size";
//     if ($speed) {
//         echo "; Speed: $speed";
//     }
//     if ($eta) {
//         echo "; ETA: $eta";
//     }
//     if ($totalTime !== null) {
//         echo "; Downloaded in: $totalTime";
//     }
// });


// $yt->setBinPath('/var/www/html/youtube-dl/youtube-dl');
// $yt->setPythonPath('/usr/bin/python3');


// //Téléchargement
// $collection = $yt->download(
//     Options::create()
//         ->downloadPath('/var/www/html/sources')
//         ->url($download_request->url)
//         ->format($format)
//         ->output($file_name)
// );


// try {
//     foreach ($collection->getVideos() as $video) {
//         if ($video->getError() !== null) {
//             throw new \Exception("Error downloading video: {$video->getError()}.");
//         } else {
//             $result = $video->getFile();
//         }
//     }
//     return $result;
// } catch (Exception $e) {
//     error_log($e);
//     //Dire a l'utilisateur que le téléchargement a échoué et qu'il doit réessayer.
//     echo 'Le téléchargement a échoué. Veuillez réessayer.';
//     //Nettoyer les données temporaires de téléchargement.
// }
