<?php

/**
 * Parse le fichier source extraits.xml pour éditer les extraits
 *
 * @package wsl 
 */

require_once 'vendor/autoload.php';
require_once 'src/utils.php';
require_once 'src/ffmpeg.php';
require_once 'src/validation.php';






//Restore default error handler.
restore_error_handler();
//Restore default exception handler.
restore_exception_handler();
