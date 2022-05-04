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
?>
<?php present_header(); ?>

<h2>Éditer un extrait</h2>


<?php dump(web_clip_path("le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4"));  ?>


<video width="320" height="240" controls>
    <source src="./le-tribunal-des-bureaux--2--plante-et-luminaire--00.08.27.300--00.09.29.325.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<?php present_footer(); ?>