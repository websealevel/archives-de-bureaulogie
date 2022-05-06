<?php
/**
 * Mapp noms roles et id (schéma BDD)
 * Description:
 *
 * @link
 *
 * @package wsl 
 */

/**
* Association des rôles et de leur id
*/
enum Roles: int {
    case Contributeur=1,
    case Moderateur=2,
    case Admin=3,
    case SuperAdmin=4,
};