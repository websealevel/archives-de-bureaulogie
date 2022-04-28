<?php

/**
 * Main page (temporaire)
 *
 * @package wsl 
 */

require_once 'src/header.php';

echo 'ackboo out of context' . PHP_EOL;

$download_request = new DownloadRequest(
    'https://www.youtube.com/watch?v=ilhsfnSCQYE',
    'Foo bar',
    '1'
);

action_download_video($download_request);

require_once 'src/footer.php';
