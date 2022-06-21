<?php

/**
 * Contient les fonctions de chemin absolus
 *
 * @link
 *
 * @package wsl 
 */


/**
 * Retourne le chemin absolu des sources sur le serveur
 * @return string Le path des downloads
 */
function web_sources_path(): string
{
    $path = sprintf("%s", PATH_SOURCES);
    return $path;
}

/**
 * Retourne le chemin absolu des clips sur le serveur
 * @return string Le path des clips
 */
function web_clips_path(): string
{
    $path = sprintf("%s", PATH_CLIPS);
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
