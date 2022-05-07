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

<?php

//Check si : user authentifié, user a la capacité, si la capacité necessite une deuxieme authentification
//Check si une 2eme authentification est demandée (sécuritée renforcée sur certaine actions). Si c'est le cas redirect vers un petit écran confirmation du mot de passe.
// if (is_authentification_confirmation_required($cap)) {
//     //Redirige vers un formulaire de login
//     redirect('/authentification-confirmation');
// }

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