<?php

/**
 * Instance de ffpmeg/ffprobe configuré sur le path local
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

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
/**
 * Retourne une instance de ffrpobe avec une config locale
 * @return FFProbe
 */
function ffbprobe_instance(): FFProbe
{
    $ffprobe = FFMpeg\FFProbe::create(array(
        'ffmpeg.binaries'  => FFMPEG_BINARIES,
        'ffprobe.binaries' => FFPROBE_BINARIES,
        'timeout'          => intval(FFMPEG_TIMEOUT),
        'ffmpeg.threads'   => intval(FFMPEG_THREADS),
    ));
    return $ffprobe;
}
