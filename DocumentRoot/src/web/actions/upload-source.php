<?php

/**
 * Traite le formulaire de login
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/FormInput.php';
require_once __DIR__ . '/../../models/InputValidation.php';
require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../../models/Credentials.php';
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../database/repository-accounts.php';

/**
 * Authentifie l'utilisateur
 * @global array $_POST
 * @global array $_SESSION
 */
function upload_source()
{
    echo 'upload_source';
}
