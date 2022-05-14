<?php

/**
 * Toutes les fonctions checkant et gérant l'état de l'user courant ainsi que ses droits
 *
 * @package wsl 
 */


require_once __DIR__ . '/database/repository-accounts.php';
require_once __DIR__ . '/database/repository-roles-capabilities.php';

/**
 * Retourne vrai si l'utilisateur courant est connecté, faux sinon
 * @return bool
 */
function is_current_user_logged_in(): bool
{
    if (!isset($_SESSION))
        throw new Exception("Aucune session active");
    return boolval(from_session('user_authentificated') ?? false);
}

/**
 * Retourne le pseudo de l'utilisateur authentifié
 * @return string
 */
function current_user_pseudo(): string
{
    if (!is_current_user_logged_in())
        throw new Exception("L'utilisateur devrait être authentifié quand on chercher son pseudo");
    return from_session('pseudo') ?? 'cher inconnu';
}

/**
 * Retourne vrai si l'utilisateur authentifié a la capacité de faire cette action, faux sinon
 * @param string $cap La capacité à tester
 * @return bool
 * @throws Exception - Si la capacité n'existe pas
 */
function current_user_can(string $cap): bool
{

    //Check que l'utilisateur est authentifié
    if (!is_current_user_logged_in()) {
        return false;
    }

    //Check que la cap existe
    if (!cap_exists($cap)) {
        throw new Exception("La capacité " . $cap . "n'existe pas.");
    }
    $authentificated_user_id = from_session('account_id');

    if (empty($authentificated_user_id))
        return false;

    $account_role = sql_find_role_of_account($authentificated_user_id);

    //Check si le role a la cap
    return role_has_cap($account_role, $cap);
}

/**
 * Retourne vrai si le mot de passe renseigné correspond à celui du compte authentifié en session, faux sinon
 * @param int $id - L'id de l'utilisateur authentifié en session
 * @param string $password_confirmation - La nouvelle demande de mot de passe
 * @return bool
 * @throws Exception - Si l'id du compte ne correspond à rien en base
 */
function confirm_current_user_identity(int $authentificated_user_id, string $password_confirmation): bool
{

    $account = sql_find_account_by_id($authentificated_user_id);

    if (!$account) {
        throw new Exception("L'id de l'utilisateur authentifié ne correspond à aucun compte enregistré en base.");
    }

    if (!is_string($password_confirmation) || !is_string($password_confirmation)) {
        throw new Exception("Passwords ne sont pas au format attendu de chaine de caractères.");
    }

    //Si trouvé, on check mdp, si pas ok, on rejette
    if (!password_verify($password_confirmation, $account->password)) {
        redirect('/authentification-confirmation', 'notices', array(
            new Notice("Le mot de passe est incorrect. Veuillez ré-essayer s'il vous plaît.", NoticeStatus::Error)
        ));
    }

    //Redirige vers l'action

    dd("Ok c'est bien toi, tu as le droit de faire ça.");

    return false;
}
