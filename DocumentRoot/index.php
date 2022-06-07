<?php

/**
 * Point d'entrée de l'application
 *
 * @package wsl 
 */


require_once './src/models/Notice.php';
require_once './src/path.php';
require_once './src/web/environment.php';
require_once './src/web/router/router.php';
require_once './src/handlers.php';

load_env();

if (in_maintenance_mode()) {
    require_once './src/web/templates/maintenance.php';
    exit;
}


resolve();
