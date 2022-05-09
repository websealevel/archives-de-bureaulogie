<?php

/**
 * Lancer des instances de ffpmeg/ffprobe configuré avec les bin sur le path local
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

autload_core();

require_once __DIR__ . '/const.php';

use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;

/**
 * Retourne une instance de ffmpeg avec une config locale
 * @return FFMpeg
 */
function ffpmeg_instance(): FFMpeg
{
    $ffmpeg = FFMpeg\FFMpeg::create(array(
        'ffmpeg.binaries'  => FFMPEG_BINARIES,
        'ffprobe.binaries' => FFPROBE_BINARIES,
        'timeout'          => intval(FFMPEG_TIMEOUT),
        'ffmpeg.threads'   => intval(FFMPEG_THREADS),
    ));

    return $ffmpeg;
}