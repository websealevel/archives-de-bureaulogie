<?php

/**
 * Le formulaire de login
 *
 * @package wsl 
 */

require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../environment.php';

?>

<?php present_header(); ?>

<?php present_template_part('form-login'); ?>

<?php present_footer(); ?>