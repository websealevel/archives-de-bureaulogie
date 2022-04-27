<?php

/**
 * Main page (temporaire)
 *
 * @package wsl 
 */

require_once 'vendor/autoload.php';
require_once 'src/actions.php';

action_update_clips();

require_once 'src/default-handlers.php';
