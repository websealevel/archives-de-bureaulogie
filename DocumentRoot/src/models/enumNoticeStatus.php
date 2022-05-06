<?php
/**
 * Les status d'une notice
 *
 * @link
 *
 * @package wsl 
 */

enum NoticeStatus: string {
    case Success = 'success' ;
    case Error = 'error';
    case Warning = 'warning';
    case ExceptionThrown = 'exception occured';
}
