<?php

/**
 * Liste des fonctions manipulant les fichiers
 *
 * @package wsl 
 */

/**
 * Retourne la liste des fichiers et des dossiers dans un dossier
 * @param string $path Le path du dossier à scanner
 * @param array $files_to_exclude. Optional. Default array(). Une liste de fichiers à exclure du scan
 * @return array
 */
function list_files_in_dir(string $path, array $files_to_exclude = array()): array
{
    //On récupere la liste des extraits
    $files = scandir($path);
    if (false === $files)
        return array();

    return array_diff($files, $files_to_exclude);
}

/**
 * Retourne vrai si le fichier de la vidéo source existe, faux sinon
 * @param string $source_name Le nom de la source
 * @return bool
 */
function source_exists(string $source_name): bool
{
    $path_source = PATH_SOURCES . '/' . $source_name;
    return file_exists($path_source);
}

/**
 * Supprime un fichier, renvoie vrai si la suppression a réussi, faux sinon
 * @param string $file_name Le nom du fichier à supprimer
 * @return bool
 */
function delete_file_clip(string $file_name): bool
{
    $path = PATH_CLIPS . '/' . $file_name;
    write_log($path);
    return $path;
    // return unlink($path);
}
