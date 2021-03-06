<?php

/**
 * Classe wrapper d'un input de formulaire
 * Description:
 *
 * @link
 *
 * @package wsl 
 */


class FormInput
{

    public function __construct(

        /** @var string Le nom du champ */
        public string $name,

        /** @var mixed La valeur actuelle du champ */
        public mixed $value,

        /** @var mixed Une fonction de callback de validation du champ */
        public mixed $validation_callback,

        /** @var string Est ce un champ checkbox (non présent dans $_POST si uncheck)*/
        public bool $is_checkbox = false
    ) {
    }
}
