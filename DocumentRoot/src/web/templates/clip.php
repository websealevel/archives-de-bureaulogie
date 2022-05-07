<?php

/**
 * Interface pour éditer les extraits (CRUD)
 * Description:
 *
 * @link
 *
 * @package wsl 
 */
require_once __DIR__ . '/../utils.php';
require_once __DIR__ . '/../current-user.php';
session_start();

if (!current_user_can('submit_clip'))
    redirect('/');
?>

<?php present_header(); ?>

<h2>Création d'un extrait</h2>
<a href="/">Retour</a>

<p>Une explication</p>
<video width="320" height="240" controls>
    <source src="test.mp4" type="audio/mp4">
    Your browser does not support the video tag.
</video>

<?php present_footer(); ?>