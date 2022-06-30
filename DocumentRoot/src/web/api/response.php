<?php

/**
 * 
 * Fonctions pour gérer la réponse de l'API
 *
 * @link
 *
 * @package wsl 
 */


require_once __DIR__ . '/../../models/InputValidation.php';

/**
 * Retourne une erreur de l'api au client, avec un message et un status code
 * @param InputValidation[] $input_errors Les erreurs sur chaque champ
 * @param string $code Le code HTTP de la requête
 * @return void
 */
function api_respond_with_error(array $invalid_inputs = array(
    new InputValidation('', '', 'Les données transmises ne sont pas valides')
), string $code = '403'): void
{
    header('Content-Type: application/json; charset=utf-8');
    $response =  json_encode(array(
        'statut' => $code,
        'errors' => array_map(function ($invalid_input) {
            return array(
                'name' => $invalid_input->name,
                'value' => $invalid_input->value,
                'message' => $invalid_input->message
            );
        }, $invalid_inputs)
    ));
    echo $response;
    exit;
}

/**
 * Retourne une réponse de l'api au client au format JSON, avec des données et un status code, et termine le processus php.
 * @param string $key La clef sous laquelle la valeur $data est enregistrée.
 * @param string $data Les données transmises
 * @param string $statut Le statut HTTP de la requête
 * @return void
 */
function api_respond_with_success(string|array $data, string $key = 'data', int $statut = 200): void
{
    header('Content-Type: application/json; charset=utf-8');
    $response = json_encode(array(
        'statut' => 200,
        $key => $data
    ));
    echo $response;
    exit;
}
