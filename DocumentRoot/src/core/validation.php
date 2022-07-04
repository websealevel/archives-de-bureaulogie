<?php

/**
 * Liste les fonctions de validation (fichiers, formats etc..)
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/../web/environment.php';

autload_core();

/**
 * Retourne vrai si la source existe sur la PATH_SOURCES ET qu'elle est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_SOURCES Optional. Le path des sources
 * @return bool
 */
function is_source_available(string $file_name, $path = PATH_SOURCES)
{
    if (!is_source_valid($file_name, $path))
        return false;

    $file_path = $path . '/' . $file_name;

    return file_exists($file_path);
}

/**
 * Retourne vrai si le clip existe sur la PATH_CLIPS ET qu'il est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_CLIPS Optional. Le path des extraits
 * @return bool
 */
function is_clip_available(string $file_name = SOURCE_FILE, $path = PATH_CLIPS)
{
    if (!is_clip_valid($file_name, $path))
        return false;

    $file_path = $path . '/' . $file_name;

    return file_exists($file_path);
}

/**
 * Retourne vrai si le fichier de la source (vidéo) est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_SOURCES Optional. Le path des sources
 * @return bool
 * @throws Exception - Si la vidéo source n'a pas un format vidéo valide
 */
function is_source_valid(string $file_name, $path = PATH_SOURCES)
{

    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_SOURCE) {
        $message = sprintf("La source %s n'a pas un format valide", $file_name);
        throw new Exception($message);
    }

    //Validate media file
    $ffprobe = FFMpeg\FFProbe::create(array(
        'ffprobe.binaries' => from_env('PATH_BIN_FFPROBE'),
    ));;

    if (!$ffprobe->isValid($file_path)) {
        $message = sprintf("ffprobe: la vidéo source %s n'a pas un format valide.", $file_name);
        throw new Exception($message);
    }

    return true;
}

/**
 * Retourne vrai si le clip est valide, faux sinon
 * @param string $file_name Le path du fichier extrait
 * @param string $PATH_CLIPS Optional. Le path des extraits
 * @return bool
 */
function is_clip_valid(string $file_name, $path = PATH_CLIPS)
{
    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_CLIP) {
        $message = sprintf("L'extrait %s n'a pas un format valide", $file_name);
        throw new Exception($message);
    }

    //Validate media file
    $ffprobe = FFMpeg\FFProbe::create(array(
        'ffprobe.binaries' =>  from_env('PATH_BIN_FFPROBE'),
    ));;
    if (!$ffprobe->isValid($file_path)) {
        $message = sprintf("L'extrait %s n'est pas valide.", $file_name);
        throw new Exception($message);
    }

    return true;
}


/**
 * Retourne vrai si les timecodes sont valides (format, min, max), faux sinon
 * @param DOMElement $clip L'extrait dont les timescodes sont à valider
 * @param string $file_source Le path du fichier source de l'extrait
 * @return bool
 */
function are_timecodes_valid(DOMElement $clip, string $file_source): bool
{
    $start = child_element_by_name($clip, "debut")->nodeValue;
    $end = child_element_by_name($clip, "fin")->nodeValue;

    return are_timecodes_valid_core($start, $end, $file_source);
}

/**
 * Retourne vrai si les timecodes sont valides (format, min, max), lève une exception sinon
 * @param DOMElement $clip L'extrait dont les timescodes sont à valider
 * @param string $file_source Le path du fichier source de l'extrait
 * @return bool
 * @throws Exception - Si le format du timecode start est invalide
 * @throws Exception - Si le format du timecode end est invalide
 * @throws Exception - Si start > end
 * @throws Exception - Si start < 0 ou end > durée de la vidée source
 */
function are_timecodes_valid_core(string $start, string $end, string $source): bool
{
    if (!is_timecode_format_valid($start)) {
        throw new Exception("Le format du timecode de début de l'extrait n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    if (!is_timecode_format_valid($end)) {
        throw new Exception("Le format du timecode de fin de l'extrait n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    //Checker que end > fin
    if (!is_start_timecode_smaller_than_end_timecode($start, $end)) {
        throw new Exception("Le timecode de début doit être plus petit que le timecode de fin. Veuillez les corriger.");
    }

    if (!are_timecodes_within_bounds($start, $end, $source)) {
        throw new Exception("L'extrait doit faire au moins 1 seconde et au plus 2min20s. Les timecodes doivent être compatible avec la durée de la vidéo source. Veuillez corriger les timecodes s'il vous plaît.");
    }

    return true;
}

/**
 * Retourne vrai si le timecode du début est plus petit que le timecode de fin, faux sinon
 * @see https://www.php.net/manual/fr/function.strtotime.php
 * @param string $start timecode de début de l'extrait
 * @param string $end timecode de fin de l'extrait
 * @return bool
 */
function is_start_timecode_smaller_than_end_timecode(string $start, string $end): bool
{
    //Ne fonctionne pas sur les milisecondes. Cela veut dire qu'un extrait doit faire au moins une seconde (ça me parait acceptable).

    $start_in_seconds = timecode_to_seconds($start);
    $end_in_seconds = timecode_to_seconds($end);

    return $start_in_seconds < $end_in_seconds;
}



/**
 * Retourne vrai si les timecodes sont dans les limites de la durée de la vidéo (ie debut plus grand que 0 et fin plus petit que durée de la vidéo ), et inférieur à taille max faux sinon
 * @param string $start timecode du début
 * @param string $end timecode de fin
 * @param string $file_source Le path de la vidéo source
 * @return bool
 */
function are_timecodes_within_bounds(string $start, string $end, string $file_source): bool
{

    $path_file_source = PATH_SOURCES . '/' . $file_source;

    //Validate media file
    $ffprobe = FFMpeg\FFProbe::create(array(
        'ffprobe.binaries' => from_env('PATH_BIN_FFPROBE'),
    ));;

    $source_duration_in_seconds = $ffprobe
        ->streams($path_file_source)
        ->videos()
        ->first()
        ->get('duration');

    $start_in_seconds = timecode_to_seconds($start);
    $end_in_seconds = timecode_to_seconds($end);

    $clip_duration = timecode_to_seconds($end) - timecode_to_seconds($start);

    return
        $start_in_seconds >= 0 &&
        $end_in_seconds < $source_duration_in_seconds &&
        $clip_duration < $source_duration_in_seconds && $clip_duration < MAX_CLIP_DURATION_IN_SEC;
}

/**
 * Remarque : Fonctionne vraiment ?
 * Retourne vrai si le format d'un timecode est valide, faux sinon
 * @param string $timecode Le timecode
 * @param string $format Le format attendu pour l'expression régulière
 * @return bool
 */
function is_timecode_format_valid(string $timecode, string $format = FORMAT_TIMECODE): bool
{
    $pattern = '/' . $format . '/i';
    return boolval(preg_match($pattern, $timecode));
}

/**
 * Renvoie vrai si l'url est une chaine de caractères url valide, faux sinon
 */
function is_valid_url(string $url): bool
{
    return !empty($url) && !filter_var($url, FILTER_VALIDATE_URL) === false;
}

/**
 * Retourne vrai si le nom de domaine de l'url de la vidéo à télécharger est authorisée, faux sinon
 * @global array ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM Toto
 */
function is_url_domain_authorized(string $url): bool
{
    $parse = parse_url($url);
    $domain = $parse['host'];

    return in_array($domain, ALLOWED_DOMAINS_TO_DOWNLOAD_SOURCES_FROM);
}

/**
 * Retourne vrai si les métadonnées de la vidéo source à télécharger ne contiennent que des caractères valides, faux sinon
 */
function is_download_request_valid(DownloadRequest $download_request)
{

    if (empty($download_request->series_name))
        return false;

    if (empty($download_request->id))
        return false;

    $clean_series_name = trim(strtolower($download_request->series_name));

    $clean_id = trim(strtolower($download_request->id));

    //Ne garde que les caractères alphanumériques (supprime toute ponctuation ou caractère spécial)
    $clean_series_name = preg_replace("/[^a-zA-Z 0-9 -]+/", "", $clean_series_name);

    //Ne garde que les caractères alphanumériques (supprime toute ponctuation ou caractère spécial)
    $clean_id = preg_replace("/[^a-zA-Z 0-9]+/", "", $clean_id);

    return
        $download_request->series_name === $clean_series_name
        && $download_request->id === $clean_id;
}

/**
 * Retourne vrai si le nom du fichier extrait respecte le format imposé, faux sinon
 * @param string $filename Le nom de fichier de l'extrait
 * @return bool
 * @link FORMAT_FILE_VIDEO_CLIP
 */
function clip_has_valid_filename_format(string $filename): bool
{
    $pattern = '=^' . FORMAT_FILE_VIDEO_CLIP . '$=';
    return preg_match($pattern, $filename);
}


/**
 * Retourne vrai si le nom de la source respecte le format imposé, faux sinon
 * @param string $filename Le nom de fichier de l'extrait
 * @return bool
 * @link FORMAT_FILE_VIDEO_CLIP
 */
function source_has_valid_filename_format(string $filename): bool
{
    $pattern = '=^' . FORMAT_SOURCE_NAME_VIDEO_CLIP . '$=';
    return preg_match($pattern, $filename);
}
