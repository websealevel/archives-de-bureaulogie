<?php

/**
 * Interface pour éditer les extraits (CRUD)
 *
 * @link
 *
 * @package wsl 
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';

if (!current_user_can('submit_reference'))
    redirect('/');
?>

<?php present_header(); ?>

<h2>Soumettre une référence bibliographique</h2>

<?php present_footer(); ?>