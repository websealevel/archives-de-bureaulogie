<?php

/**
 * Main page (temporaire)
 *
 * @package wsl 
 */

require_once 'vendor/autoload.php';
require_once 'src/utils.php';
require_once 'src/ffmpeg.php';
require_once 'src/validation.php';
require_once 'src/actions.php';
require_once 'src/handlers.php';

action_update_clips();


require_once 'src/default-handlers.php';
