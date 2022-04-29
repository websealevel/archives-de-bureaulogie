<?php

/**
 * Toutes les fonctions utiles pour gérer la navigation, les templates, l'escaping de l'output et les métadonnées du site.
 * @link
 *
 * @package wsl 
 */

/**
 * Retourne le titre du site
 * @return string le titre du site
 */
function site_title(): string
{
    return 'ackboo out of context';
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
        error_404();

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
        error_404();

    require $path_template;

    return;
}

/**
 * Ecrit le header sur la sortie standard (output de l'html)
 * @return void
 */
function present_header(): void
{
    present_template('header');
}
/**
 * Ecrit le footer sur la sortie standard (output de l'html)
 * @return void
 */
function present_footer()
{
    present_template('footer');
}

/**
 * Charge l'autoload.php de composer pour inclure les dépendances vendor du script appelant.
 * @return void
 */
function autoload(): void
{
    require __DIR__ . '/../../../vendor/autoload.php';
}


if (!function_exists('write_log')) {

    /**Ecrit un log
     *
     * @param mixed $log
     */
    function write_log($log)
    {

        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}
