<?php

/**
 * Point d'entrée de l'application
 *
 * @package wsl 
 */

require_once './src/web/templates/header.php';

require './src/web/router/router.php';

resolve();

require_once './src/web/templates/footer.php';
