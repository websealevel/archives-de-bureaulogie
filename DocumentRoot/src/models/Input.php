<?php
/**
 * Un modèle simple d'erreur d'input dans un formulaire
 * retourné au client
 *
 * @package wsl 
 */




/**
 * Classe servant à stocker une erreur sur un champ d'un formulaire
 * @see 
 */
class Input
{

    public function __construct(

         /** @var string Le nom du champ */
        public readonly string $input_name = "",

         /** @var mixed La valeur actuelle du champ */
        public readonly string $input_value = "",

        /** @var string Le message à afficher pour le champ */
        public readonly string $message = "",

/** @var string Le statut du champ */
public readonly string $status = ''

    ) {
    }
}
