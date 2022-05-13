<?php

/**
 * Système de routage de l'application
 * Invoqué par le point d'entrée du programme (index.php)
 * @link
 *
 * @package wsl 
 */

require_once __DIR__ . '/routes.php';
require_once __DIR__ . '/../log.php';



/**
 * Résoud la requête entrante
 */
function resolve()
{

    $path = get_path();


    //Server send events route
    if ('/sse-download-source' === $path) {
        require_once __DIR__ . '/../sse/download.php';
        exit;
    }

    $method = get_method();
    $callback = find_callback($path, $method);

    if (!is_callable($callback)) {
        present_template('500');
    }

    $callback();
}

/**
 * Redirige vers une autre route
 * @param string $path La route vers laquelle rediriger
 * @param string $session_key La clé sous laquelle enregistrer les données à mettre en session
 * @param array $data Les données à enregistrer en session
 * @param string $refresh Indiquer un temps avant de rediriger. Default 0
 * @global array $_SESSION
 * @see Routes, Router
 * @return void
 */
function redirect(string $path, string $session_key = '', array $data = array(), int $refresh = 0)
{
    if (!empty($session_key) && !empty($data) && isset($_SESSION)) {
        $_SESSION[$session_key] = $data;
    }

    if (0 === $refresh) {
        header('Location: ' . $path);
    } else {
        header("refresh:{$refresh};url={$path}");
    }

    exit;
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
            present_template('500');
        };

    if (!array_key_exists($path,  $routes[$method]))
        return function () {
            present_template('404');
        };


    return  $routes[$method][$path];
}


/**
 * Retourne le path (la ressource) demandée avec les paramètres d'URL
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
