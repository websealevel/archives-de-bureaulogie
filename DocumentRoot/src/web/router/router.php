<?php

/**
 * Système de routage de l'application
 * Invoqué par le point d'entrée du programme (index.php)
 * @link
 *
 * @package wsl 
 */

require __DIR__ . '/routes.php';


/**
 * Résoud la requête entrante
 */
function resolve()
{
    $path = get_path();
    $method = get_method();
    $callback = find_callback($path, $method);

    if (!is_callable($callback)) {
        error_500();
    }

    $callback();
}

/**
 * Retourne la callback associée à la route demandée, une erreur 500 ou 404 sinon
 * @param string $path le chemin appelé
 * @param string $method la methode HTTP appelée
 * @return callable
 */
function find_callback(string $path, string $method): callable
{

    $routes = routes();

    if (!array_key_exists($method,  $routes))
        return function () {
            error_500();
        };

    if (!array_key_exists($path,  $routes[$method]))
        return function () {
            error_404();
        };

    return  $routes[$method][$path];
}

/**
 * Ecrit une erreur 500 sur la sortie standard
 * @return void
 */
function error_500()
{
    echo 'Error 500' . PHP_EOL;
}

/**
 * Ecrit une erreur 404 sur la sortie standard
 * @return void
 */
function error_404()
{
    echo 'Error 404, la ressource est introuvable, sorry :/ ' . PHP_EOL;
}

/**
 * Retourne le path (la ressource) demandée
 * @return string
 */
function get_path(): string
{
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');

    if (false === $position)
        return $path;

    return substr($path, 0, $position);
}

/**
 * Retourne la méthode HTTP demandée
 * @return string
 */
function get_method(): string
{
    return strtolower($_SERVER['REQUEST_METHOD']);
}
