<?php

/**
 * Un modèle simple d'erreur d'input dans un formulaire
 * retourné au client
 *
 * @package wsl 
 */

require __DIR__ .'/enumInputStatus.php';

/**
 * Classe servant à stocker une erreur sur un champ d'un formulaire
 * @see 
 */
class Input
{

    public function __construct(

        /** @var string Le nom du champ */
        public  string $name = "",

        /** @var mixed La valeur actuelle du champ */
        public  string $value = "",

        /** @var string Le message à afficher pour le champ */
        public  string $message = "",

        /** @var string Le statut du champ */
        public InputStatus $status = InputStatus::Invalid

    ) {
    }
}
