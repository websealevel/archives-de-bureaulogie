<?php

/**
 * Toutes les fonctions utiles pour gérer la navigation, les templates, l'escaping et les métadonnées du site.
 * @link
 *
 * @package wsl 
 */

require __DIR__ . '/log.php';

/**
 * Retourne le titre du site
 * @return string le titre du site
 */
function site_title(): string
{
    return 'Fondation Libre de Bureaulogie';
}

/**
 * Retourne la home du site
 * @return string le titre du site
 */
function site_url(): string
{
    return '/';
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
 * Inclut les scripts js sur la sortie standards
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

