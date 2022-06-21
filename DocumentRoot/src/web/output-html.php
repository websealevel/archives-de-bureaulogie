<?php

/**
 * Contient toutes les fonctions de sortie HTML échapées
 *
 * @package wsl 
 */


require_once __DIR__ . '/markup.php';
require_once __DIR__ . '/path.php';


/**
 * Echappe et écrit du texte sur la sortie standard.
 * @param string $text Le texte à échapper et à écrire.
 * @return void
 */
function esc_html_e(string $text)
{
    echo htmlentities($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Retourne du texte échappé.
 * @param string $text Le texte à échapper.
 * @return string Le texte échappé.
 */
function esc_html(string $text): string
{
    return htmlentities($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Ecrit sur la sortie standard les sources sous la forme d'un select
 * @param string $name_attr L'attribut name du select
 * @param array $show_data Les données à afficher dans chaque option
 * @return void
 */
function esc_sources_to_html_select_e(string $name_attr = 'sources', array $show_data = array('label')): void
{
    $options = map_declared_sources_to_html_item('option', $show_data);

    $label = 'Veuillez choisir la vidéo dont vous souhaitez faire un extrait';

    echo sprintf('<label for="%s">%s</label>', $name_attr, $label);

    echo sprintf('<select name="%s" id="%s">', $name_attr, $name_attr);
    foreach ($options as $option) {
        echo $option;
    }
    echo sprintf('</select>');
    return;
}

/**
 * Ecrit sur la sortie standard les sources sous la forme d'une liste
 * @param string $name_attr L'attribut name de la liste
 * @param string $label Optional. Default no. Ajouter un label à la liste
 * @return void
 */
function esc_html_list_sources_e(string $name_attr = 'sources', array $show_data = array('label'), string $label = ''): void
{
    $options = map_declared_sources_to_html_item('li', $show_data);

    if (!empty($label)) {
        $label = 'Liste des vidéos sources déjà présentes';
        echo sprintf('<label for="%s">%s</label>', $name_attr, $label);
    }

    echo sprintf('<ul name="%s">', $name_attr);
    foreach ($options as $option) {
        echo $option;
    }
    echo sprintf('</ul>');
    return;
}


/**
 * Ecrit sur la sortie standard l'url d'un clip (attribut source tag video)
 * @param string $clip_name
 * @return void
 */
function esc_video_clip_url_e(string $clip_name)
{
    $url = web_clip_path($clip_name);
    if ($url !== filter_var($url, FILTER_SANITIZE_URL)) {
        throw new Exception("L'url de l'extrait n'est pas valide.");
    }
    echo $url;
    return;
}
/**
 * Ecrit sur la sortie standard l'url d'une vidéo source (attribut source tag video)
 * @param string $source_name
 * @return void
 */
function esc_video_source_url_e(string $source_name)
{
    $url = web_clip_path($source_name);
    if ($url !== filter_var($url, FILTER_SANITIZE_URL)) {
        throw new Exception("L'url de l'extrait n'est pas valide.");
    }
    echo $url;
    return;
}

/**
 * Ecrit sur la sortie standard l'historique des téléchargements de vidéos sources sous la forme d'items li
 * @return void
 */
function esc_download_history_items_e(): void
{
    $history = download_history();
    foreach ($history as $download) {

        $account = find_account_by_id($download['account_id']);

        $created_on = date('d-m-Y à H:i:s', strtotime($download['created_on']));

        $item = sprintf(
            '
        <li class="download-state %s">utilisateur: %s - url: %s - démarré le: %s - temps total de téléchargement (min): %s - nom du fichier: %s - statut: %s
        </li>',
            $download['state'],
            $account->pseudo,
            $download['url'],
            $created_on,
            $download['totaltime'],
            $download['filename'],
            $download['state']
        );

        echo ($item);
    }
    return;
}
/**
 * Ecrit sur la sortie standard le nombre de téléchargements en cours
 * @return void
 */
function esc_active_downloads_info_e()
{
    $active_downloads = active_downloads();
    if (0 === count($active_downloads))
        return;
    esc_html_e(sprintf(" - %d téléchargement(s) en cours", count($active_downloads)));
    return;
}

/**
 * Ecrit sur la sortie standard le fil d'ariane.
 * @param string $relative_path Le chemin vers lequel le fil pointe.
 * @return void
 */
function esc_html_breadcrumbs(string $relative_path = '/'): void
{
    $uri_without_breadcrumbs = array(
        '/',
        '/confirm-authentification'
    );

    if (in_array($_SERVER['REQUEST_URI'], $uri_without_breadcrumbs))
        return;
?>
    <div class="fil-arianne">
        <a href="<?php echo $relative_path; ?>">Retour</a>
    </div>
<?php
    return;
}

/**
 * Ecrit les scripts js sur la sortie standard dans une balise script.
 * @param array $scripts Les scripts js à sortir sur la sortie standard.
 * @return void
 */
function enqueue_js_scripts(array $scripts = array())
{
    if (empty($scripts))
        return;


    echo '<script type="text/javascript">' . PHP_EOL;
    echo 'const PHPSESSID="' . session_id() . '";' . PHP_EOL;
    foreach ($scripts as $script) {
        $js_script_path = sprintf("%s/js/%s.js", ASSETS_PATH, $script);
        require $js_script_path;
    }
    echo '</script>';
    return;
}

/**
 * Ecrit et échappe sur la sortie standard une valeur enregistrée dans la session
 * @param string $key La clef demandée
 * @param string $array_key Optional Default = ''. Si la valeur demandée est un tableau, renvoie la valeur sous la clef $array_key
 * @return void
 */
function esc_html_from_session_e(string $key, string $array_key): void
{
    $session_value = from_session($key, $array_key);
    esc_html_e($session_value);
}

/**
 * Ecrit sur la sortie standard le message d'erreur associé à l'input du form s'il existe, rien sinon
 * @param string $input_name Le nom du champ du formuliare
 * @param InputValidation[] $form_errors
 * @throws Exception - Si aucune session n'est ouverte
 * @return void
 */
function esc_html_form_error_msg_e(string $input_name, string $key_form_errors)
{

    if (!isset($_SESSION) || !isset($_SESSION["{$key_form_errors}"]))
        return;

    $form_errors = $_SESSION["{$key_form_errors}"];

    if (!array_key_exists($input_name, $form_errors))
        return;

    echo '<div class="error-message">';
    esc_html_e($form_errors["{$input_name}"]->message);
    echo '</div>';
    return;
}

/**
 * Ecrit sur la sortie standard les notices présentes en session
 * @global $_SESSION
 * @throws Exception - Si aucune session n'est ouverte
 */
function esc_html_notices_e()
{
    if (!isset($_SESSION) || !isset($_SESSION['notices']))
        return;

    $notices = $_SESSION['notices'];

    if (!is_array($notices))
        throw new Exception("@esc_html_notices_e: les notices ne sont pas empilées dans un tableau");

    $html_notices = array_map(function (Notice $notice) {
        return '<li class="notice ' . $notice->status->value . '">' . $notice->message . '</li>';
    }, $notices);

    echo '<ul class="notices">';
    foreach ($html_notices as $html_notice) {
        echo $html_notice;
    }
    echo '</ul>';

    unset($_SESSION['notices']);
}
