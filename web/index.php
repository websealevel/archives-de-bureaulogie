<?php

/**
 * Main page (temporaire)
 *
 * @package wsl 
 */

require_once 'src/header.php';

echo 'ackboo out of context' . PHP_EOL;

$download_request = new DownloadRequest('https://www.youtube.com/watch?v=54fea7wuV6s', 'Le tribunal des bureaux', '2');

action_download_source_video($download_request);

require_once 'src/footer.php';


