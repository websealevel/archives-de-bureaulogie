<?php

/**
 * Traite le formulaire de logout
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/../../models/Notice.php';
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../actions/log-out.php';
require_once __DIR__ . '/../log.php';


/**
 * Remarque : le redirect ne marche pas quand une erreur tombe dans handler général.
 * Déconnecte un utilisateur (en session)
 * @return void
 * @global $_SESSION
 */
function log_out(Notice $notice = new Notice("Vous avez été déconnecté·e avec succès", NoticeStatus::Success))
{
    //2 cas de figure : soit deconnecté en appelant directement log-out
    //                  soit déconnecté suite à une exception (et headers peuvent être déjà envoyés)

    if (!isset($_SESSION)) {

        if (!isset($_SESSION['user_authentificated']) || !$_SESSION['user_authentificated']) {
            exit;
        }
    }

    if (headers_sent()) {
        //Ca pose des problèmes c'est pas ouf.
        //Headers déjà envoyé, exception envoyée durant l'excution d'un template. Je redirige pas, j'indique en session que l'utilisateur n'est plus connecté.
        session_unset();
        $_SESSION['notices'] = array($notice);
        present_template_part('form-login');
        present_footer();
    } else {
        //Sinon, c'est une action de log-out classique ou en début de script, je peux rediriger directement
        session_regenerate_id();
        $pseudo = $_SESSION['pseudo'] ?? 'Inconnu';
        error_log_out_success($pseudo, $notice);
        session_unset();
        redirect('/', 'notices', array(
            $notice
        ));
    }

    return;
}
