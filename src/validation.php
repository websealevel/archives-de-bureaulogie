<?php

/**
 * Liste les fonctions de validation (fichiers, formats etc..)
 *
 * @package wsl 
 */


/**
 * Retourne vrai si le fichier source est valide (validation via le DTD), faux sinon
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return bool
 */
function is_source_file_valid(string $file_source = SOURCE_FILE): bool
{
    $dom = new DOMDocument();

    $dom->preserveWhiteSpace = false;

    $dom->load($file_source);

    return $dom->validate();
}

/**
 * Retourne vrai si la source existe sur la PATH_SOURCES ET qu'elle est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_SOURCES Optional. Le path des sources
 * @return bool
 */
function is_source_available(string $file_name = SOURCE_FILE, $path = PATH_SOURCES)
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
 */
function is_source_valid(string $file_name, $path = PATH_SOURCES)
{

    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_SOURCE) {

        $message = sprintf("La source %s n'a pas un format valide", $file_name);
        throw new Exception($message);
    }

    return true;
}

/**
 * Retourne vrai si le clip est valide, faux sinon
 * @param string $file_source Optional. Le fichier source 
 * @param string $PATH_CLIPS Optional. Le path des extraits
 * @return bool
 */
function is_clip_valid(string $file_name = SOURCE_FILE, $path = PATH_CLIPS)
{
    $file_path = $path . '/' . $file_name;

    $info = pathinfo($file_path);

    if ($info["extension"] !== EXTENSION_CLIP) {

        $message = sprintf("L'extrait %s n'a pas un format valide", $file_name);
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

    if (!is_timecode_format_valid($start)) {
        throw new Exception("Le format du timecode de début de l'extrait " . $clip->getAttribute("slug") . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    if (!is_timecode_format_valid($end)) {
        throw new Exception("Le format du timecode de fin de l'extrait " . $clip->getAttribute("slug") . " n'est pas valide. Veuillez le corriger (voir la documentation).");
    }

    //Checker que end > fin
    if (!is_start_timecode_smaller_than_end_timecode($start, $end)) {
        throw new Exception("Les timecodes de début et de fin de l'extrait " . $clip->getAttribute("slug") . " ne sont pas valides. Le timecode de début doit être plus petit que le timecode de fin (doit référencer un moment antérieur dans la vidéo). Veuillez le corriger (voir la documentation).");
    }

    if (!are_timecodes_within_bounds($start, $end, $file_source)) {
        throw new Exception("Les timecodes de début et de fin de l'extrait " . $clip->getAttribute("slug") . " ne sont pas valides. Le timecode de début doit être plus grand que l'instant 0 et le timecode de fin doit être plus petit que la durée de la vidéo. Veuillez les corriger.");
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
    return strtotime($start) < strtotime($end);
}


/**
 * Retourne vrai si les timecodes sont dans les limites de la durée de la vidéo (ie debut plus grand que 0 et fin plus petit que durée de la vidéo ), faux sinon
 * @param string $start timecode du début
 * @param string $end timecode de fin
 * @param string $file_source Le path de la vidéo source
 * @return bool
 */
function are_timecodes_within_bounds(string $start, string $end, string $file_source): bool
{

    $file_source = 'sources/le-tribunal-des-bureaux-2.mp4';

    $ffprobe = FFMpeg\FFProbe::create();

    $source_duration = $ffprobe
        ->streams($file_source)
        ->videos()
        ->first()
        ->get('duration');

    $clip_duration = strtotime($end) - strtotime($start);

    return
        strtotime($start) > 0 &&
        strtotime($end) < $source_duration &&
        $clip_duration < $source_duration;
}

/**
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
