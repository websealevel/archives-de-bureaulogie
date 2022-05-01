<?php
/**
 * Le statut d'un champ d'un formulaire après validation
 *
 * @package wsl 
 */

enum InputStatus: string {
    case Valid : 'valid';
    case Invalid: 'invalid';
}
