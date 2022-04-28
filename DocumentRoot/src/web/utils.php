<?php

/**
 * Toutes les fonctions utiles pour gérer aider à gérer l'application web
 * @link
 *
 * @package wsl 
 */

/**
 * Retourne le titre du site
 * @return string le titre du site
 */
function site_title():string
{
    return 'ackboo out of context';
}

/**
 * Retourne l'url du site
 * @return string le titre du site
 */
function site_url():string
{
    return 'ackboooutofcontext.websealevel.com';
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
function enqueue_js_scripts(){

}