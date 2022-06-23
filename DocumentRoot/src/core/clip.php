<?php

/**
 * Liste des fonctions générant les extraits (clips)
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/query.php';
require_once __DIR__ . '/journaling.php';


/**
 * Génére les clips déclarés dans le fichier source 
 * @param string file_source Optional. Le fichier source déclarant les extraits (au format XML) 
 * @return array La liste des extraits générés, déjà existants et invalides
 */
function generate_clips(string $file_source = SOURCE_FILE): array
{
    //On récupere les extraits déclarés
    $declared_clips = query_declared_clips($file_source);

    $results = array(
        "already_exists" => array(),
        "created" => array(),
        "invalid" => array()
    );

    foreach ($declared_clips as $clip) {

        try {

            $declared_source = declared_source_of($clip);

            //On récupere le nom de la source réeelle
            $filename_source_video = source_name($declared_source);

            //On vérifie que la source est disponible
            if (!is_source_available($filename_source_video)) {
                $message = "La source déclarée " . $filename_source_video . " n'a pas été uploadée sur le serveur. Veuillez l'uploader d'abord.";
                throw new Exception($message);
            }

            //On vérifie que les timecodes sont valides
            if (!are_timecodes_valid($clip, $filename_source_video)) {
                $clip_slug = $clip->getAttribute("slug");
                $message = "Les timescodes de l'extrait " . $clip_slug . " ne sont pas valides. Veuillez les corriger.";
                throw new Exception($message);
            }

            //On vérifie que le clip n'existe pas déjà.
            $path_clip_created = clip_path($clip);

            if (file_exists($path_clip_created)) {
                $file_name = str_replace(PATH_CLIPS . "/", "", $path_clip_created);
                $results["already_exists"][] = $file_name;
                continue;
            } else {

                //Tout est valide on peut passer à la génération du clip
                $results["created"][] = clip_source($clip, $filename_source_video);
            }
        } catch (Exception $e) {

            $results['invalid'][] = str_replace(PATH_CLIPS . "/", "", clip_path($clip));
        }
    }

    return $results;
}
