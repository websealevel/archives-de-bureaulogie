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
 * Charge l'autoload.php de composer pour inclure les dépendances vendor du script appelant.
 * @return void
 */
function autoload(): void
{
    require_once __DIR__ . '/../../vendor/autoload.php';
}


/**
 * Retourne le titre du site.
 * @return string le titre du site.
 */
function site_title(): string
{
    return 'archives de bureaulogie';
}

/**
 * Retourne la home du site.
 * @return string le titre du site.
 */
function home_rel_url(): string
{
    return '/';
}

/**
 * Retourne le nom de domaine du site.
 * @return string
 */
function host(): string
{
    return filter_input(INPUT_SERVER, 'HTTP_HOST');
}

/**
 * Retourne l'url du site (protocole et nom de domaine).
 * @return string
 */
function site_url(): string
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = host();
    return sprintf("%s%s", $protocol, $domain);
}

/**
 * Ecrit sur la sortie standard le template demandé.
 * @param string $template_name Le nom du template dans le dossier templates.
 * @return void
 */
function present_template(string $template_name)
{
    $template = strtolower(trim($template_name)) . '.php';
    $path_template = __DIR__ . '/templates/' . $template;

    //Possible infinite loop si le template 404 n'existe pas.
    if (!file_exists($path_template))
        present_template('404');

    require_once $path_template;
    exit;
}

/**
 * Ecrit sur la sortie standard le template part demandé.
 * @param string $template_name Le nom du template part dans le dossier templates/parts.
 * @return void
 */
function present_template_part(string $template_part, array $js_scripts = array())
{
    $template = strtolower(trim($template_part)) . '.php';

    $path_template = __DIR__ . '/templates/parts/' . $template;

    if (!file_exists($path_template))
        present_template('404');

    require $path_template;

    return;
}

/**
 * Ecrit le header sur la sortie standard (output de l'html).
 * @return void
 */
function present_header(): void
{
    present_template_part('header');
}
/**
 * Ecrit le footer sur la sortie standard (output de l'html)
 * @param array $js_dependencies Scripts JS à enqueue
 * @return void
 */
function present_footer(array $js_scripts = array())
{
    present_template_part('footer', $js_scripts);
}

/**
 * Retrouve la valeur POSTée d'un input de formulaire.
 * @param InputValidation|string|array $input La valeur d'un input.
 * @return InputValidation|string|array La valeur POSTé de l'input.
 */
function retrieve_value(InputValidation|string|array $input): string
{
    if ($input instanceof InputValidation)
        return $input->value;

    return $input;
}

/**
 * Retourne le résultat des callback de validation enregistrées sur chaque input
 * @param FormInput[] $inputs Les inputs et leur callbacks de validation associés
 * @return InputValidation[] Les résultats de validation des inputs.
 * @global array $_POST
 * @throws Exception - Si la callbacl de validation n'est pas de type Callable
 */
function validate_posted_form(array $inputs): array
{
    $input_validations = array();

    foreach ($inputs as $input) {

        $name = $input->name;

        //Check que le champ est POSTé si c'est pas une checkbox
        if (!isset($_POST["{$name}"]) && !$input->is_checkbox) {
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
 * Retourne vrai si la chaine de caractères est un json valide, faux sinon
 * @param string $string La chaine de caractère json a valider
 * @return bool
 */
function is_valid_json(string $json): bool
{
    try {
        $test = json_decode($json, null, flags: JSON_THROW_ON_ERROR);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
