<?php

/**
 * Toutes les fonctions utiles pour gérer la navigation, les templates, l'escaping et les métadonnées du site.
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/log.php';
require_once __DIR__ . '/../core/const.php';

/**
 * Retourne le titre du site
 * @return string le titre du site
 */
function site_title(): string
{
    return 'Archives de Bureaulogie';
}

/**
 * Retourne la home du site
 * @return string le titre du site
 */
function home_rel_url(): string
{
    return '/';
}

/**
 * Retourne le nom de domaine du site
 * @return string
 */
function host(): string
{
    return filter_input(INPUT_SERVER, 'HTTP_HOST');
}

/**
 * Retourne l'url du site (protocole et nom de domaine)
 * @return string
 */
function site_url(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = host();
    return sprintf("%s%s", $protocol, $domain);
}

/**
 * Echappe et écrit du texte sur la sortie standard
 * @param string $text Le texte à échapper et à écrire
 * @return void
 */
function esc_html_e(string $text)
{
    echo htmlentities($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Retourne du texte échappé
 * @param string $text Le texte à échapper
 * @return string Le texte échappé
 */
function esc_html(string $text): string
{
    return htmlentities($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Inclut les scripts js sur la sortie standards dans le footer.
 */
function enqueue_js_scripts()
{
}

/**
 * Ecrit sur la sortie standard le template demandé
 * @param string $template_name Le nom du template dans le dossier templates
 * @return void
 */
function present_template(string $template_name)
{
    $template = strtolower(trim($template_name)) . '.php';
    $path_template = __DIR__ . '/templates/' . $template;

    //Possible infinite loop si le template 404 n'existe pas.
    if (!file_exists($path_template))
        present_template('404');

    require $path_template;

    return;
}

/**
 * Ecrit sur la sortie standard le template part demandé
 * @param string $template_name Le nom du template part dans le dossier templates/parts
 * @return void
 */
function present_template_part(string $template_part)
{

    $template = strtolower(trim($template_part)) . '.php';
    $path_template = __DIR__ . '/templates/parts/' . $template;

    if (!file_exists($path_template))
        present_template('404');

    require $path_template;

    return;
}

/**
 * Ecrit le header sur la sortie standard (output de l'html)
 * @return void
 */
function present_header(): void
{
    present_template_part('header');
}
/**
 * Ecrit le footer sur la sortie standard (output de l'html)
 * @return void
 */
function present_footer()
{
    present_template_part('footer');
}

/**
 * Charge l'autoload.php de composer pour inclure les dépendances vendor du script appelant.
 * @return void
 */
function autoload(): void
{
    require_once __DIR__ . '/../../../vendor/autoload.php';
}

/**
 * Retrouve la valeur POSTée d'un input de formulaire
 * @param InputValidation|string|array $input La valeur d'un input
 * @return string La valeur POSTé de l'input
 */
function retrieve_value(InputValidation|string|array $input): string
{
    if ($input instanceof InputValidation)
        return $input->value;

    return $input;
}


/**
 * Valide les champs de formulaires passés en argument et retourne la validation sous forme d'un tableau mappant les inputs.
 * @param FormInput[] $inputs Les inputs demandés
 * @return InputValidation[] Un tableau de validation d'inputs
 * @global array $_POST
 */
function validate_posted_form(array $inputs): array
{

    $input_validations = array();

    foreach ($inputs as $input) {

        $name = $input->name;

        //Check que le champ est POSTé.
        if (!isset($_POST["{$name}"])) {
            $input_validations["{$name}"] = new InputValidation(
                $name,
                "",
                "Le champ ne peut pas être vide, veuillez le remplir."
            );
            continue;
        }

        //Si champ POSTé, appelle la callback de validation.
        $validation_callback = $input->validation_callback;

        if (!is_callable($validation_callback)) {
            throw new Exception("La callback de validation n'est pas callable.");
        }

        $input_validations["{$name}"] = $validation_callback($input->value);
    }

    return $input_validations;
}

/**
 * Retourne le path des clips sur le serveur
 * @return string Le path des clips
 */
function web_clips_path(): string
{
    $path = sprintf("%s", PATH_CLIPS);
    return $path;
}

/**
 * Retourne le path des downloads sur le serveur
 * @return string Le path des downloads
 */
function web_downloads_path(): string
{
    $path = sprintf("%s", PATH_DOWNLOADS);
    return $path;
}

/**
 * Retourne le path des sources sur le serveur
 * @return string Le path des downloads
 */
function web_sources_path(): string
{
    $path = sprintf("%s", PATH_SOURCES);
    return $path;
}

/**
 * Retourne le path d'un clip sur le serveur
 * @return string Le path des clips
 * @throws Exception - Si le clip n'existe pas
 */
function web_clip_path(string $clip_name): string
{
    $path = web_clips_path();
    $clip_path = sprintf("%s/%s", $path, $clip_name);
    if (!file_exists($clip_path))
        throw new Exception("L'extrait n'existe pas sur le serveur.");
    return $clip_path;
}

/**
 * Ecrit sur la sortie standard l'url du clip
 * @param string $clip_name Le nom du clip demandé
 * @return void
 */
function esc_web_clip_path_url_e(string $clip_name)
{
    $url = web_clip_path($clip_name);
    if ($url !== filter_var($url, FILTER_SANITIZE_URL)) {
        throw new Exception("L'url de l'extrait n'est pas valide.");
    }
    echo $url;
}
