<?php

/**
 * Classe wrapper d'une validation d'un input de formulaire (référencé ici par son nom $name)
 *
 * @package wsl 
 */

require __DIR__ .'/enumInputStatus.php';

/**
 * Classe servant à stocker la validation d'un champ de formulaire
 * @see 
 */
class InputValidation
{

    public function __construct(

        /** @var string Le nom du champ de formulaire référencé */
        public  string $name = "",

        /** @var string Le message à afficher pour le champ */
        public  string $message = "",

        /** @var string Le statut de validation du champ */
        public InputStatus $status = InputStatus::Invalid

    ) {
    }
}
