<?php
/**
 * Les status d'un téléchargement
 *
 * @link
 *
 * @package wsl 
 */

enum DownloadState: string {
    case Pending = 'pending' ;
    case Downloading = 'downloading';
    case Downloaded = 'downloaded';
    case Failed= 'failed';
}
