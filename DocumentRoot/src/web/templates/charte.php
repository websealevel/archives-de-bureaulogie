<?php

/**
 * La page de la charte du site
 * Description:
 *
 * @link
 *
 * @package wsl 
 */
require_once __DIR__ . '/../utils.php';

present_header();
?>
<h2>Charte des Archives de Bureaulogie</h2>

<p>En crééant un compte sur
    <a href="<?php echo $_SERVER['HTTP_HOST']; ?>">
        <?php echo $_SERVER['HTTP_HOST']; ?>
    </a> <em> vous êtes engagé·e à respecter cette charte</em>.
</p>

<p>Toute contribution ou attitude provenant d'un compte qui ne respecte pas la charte sera suspendu, voire supprimé.</p>


<p class="signature">Les Archives de Bureaulogie</p>
<p class="contact"> <a href="/contact">Nous contacter</a> </p>

<?php
present_footer();
