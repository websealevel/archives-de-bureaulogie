<?php

/**
 * Liste toutes les requêtes (lecture et écriture) XPATH sur le fichier source (sources et extraits déclarés)
 *
 * @package wsl 
 */

require_once __DIR__ . '/const.php';
require_once __DIR__ . '/xml.php';
require_once __DIR__ . '/validation.php';


/**
 * Retourne la liste des sources déclarées dans le fichier source
 * @param string $file_source Optional. Le fichier source
 * @return DOMNodeList
 */
function query_declared_sources(string $file_source = SOURCE_FILE): DOMNodeList
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $sources = $xpath->query('//ns:extraits/ns:source');

    if (!$sources)
        return new DOMNodeList();

    //Check que les sources déclarées correspondent aux sources présentes
    $not_found = array_filter(iterator_to_array($sources), function ($source) {
        return !is_source_available($source->getAttribute('name'));
    });

    if (!empty($not_found)) {
        $message = sprintf("%d sources sont déclarées dans le fichier source mais ne sont pas présentes sur le serveur !", count($not_found));
        throw new Exception($message);
    }

    return $sources;
}

/**
 * Retourne la liste des extraits déclarés dans le fichier source
 * @param string $file_source Optional. Le fichier source
 * @return DOMNodeList
 */
function query_declared_clips(string $file_source = SOURCE_FILE): DOMNodeList
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $result = $xpath->query('//ns:extrait');

    return $result;
}

/**
 * Retourne l'élément source dont l'attribut attr_name a la valeur $value, faux si aucun match
 * @param string $attr_name Le nom de l'attribut
 * @param string $value La valeur de l'attribut name de la source rechercé
 * @return DOMNode|bool 
 * @throws Exception - Si la liste contient plus d'un résultat (chaque source avoir un attribut attr_name à la valeur unique)
 * @throws Exception - Si l'attribut demandé n'a  pas une contrainte (soit DTD via ID soit métier) d'unicité sur sa valeur.
 */
function query_source_by_unique_attr(string $attr_name, string $value, string $file_source = SOURCE_FILE): DOMNode|bool
{
    $unique_attributes = array(
        'name',
        'url'
    );

    if (!in_array($attr_name, $unique_attributes))
        throw new Exception("Cette fonction ne doit être utilisée que pour requêter des éléments avec des attributs dont la valeur doit être unique dans le document. Les attributs autorisés sont: " . implode($unique_attributes));

    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    $query = sprintf("//ns:extraits/ns:source[@%s='%s']", $attr_name, $value);

    $match = $xpath->query($query);

    if ($match->count() > 1)
        throw new Exception(sprintf("Il existe deux sources avec le même attribut %s, chaque attribut %s doit avoir une valeur unique", $attr_name, $attr_name));

    if (1 == $match->count())
        return $match->item(0);

    return false;
}

/**
 * Retourne l'élément clip demandé
 * @param string $source La source du clip (son parent)
 * @param string $slug Le slug du clip
 * @param string $timecode_start
 * @param string $timecode_end
 * @param string $return Ce qu'on veut retourner (un DOMNode ou un Clip)
 * @return DOMNode|Clip|false
 */
function query_clip(string $source, string $slug, string $timecode_start, string $timecode_end, string $return = 'node', string $file_source = SOURCE_FILE): DOMNode|Clip|false
{
    $xpath = load_xpath($file_source, XMLNS_SOURCE_FILE);

    die;


    // $query = sprintf("//ns:extraits/ns:source[@%s='%s']", $attr_name, $value);

    $match = $xpath->query($query);

    //Checks

    return match ($return) {
        'node' => $match,
        'model' => new Clip(),
        default => false
    };
}

/**
 * Ajoute un extrait au fichier la source s'il n'existe pas déjà (source+timecodes identiques)
 * @param string $source La source du clip (son parent)
 * @param string $slug Le slug du clip
 * @param string $timecode_start
 * @param string $timecode_end
 * @return DOMNode Le noeud ajouté
 */
function declare_clip(string $source, string $slug, string $timecode_start, string $timecode_end, string $file_source = SOURCE_FILE): DOMNode
{
}

/**
 * Ajoute un élément enfant source au noeud root. Retourne vrai si l'ajout a été bien enregistré, faux sinon
 * @param string $url L'URL de la vidéo source
 * @param string $series Le nom de la série à laquelle appartient la vidéo source
 * @param string $slug Le slug de la vidéo
 * @param string $file_name Le nom du fichier de la vidéo source
 * @return bool 
 * @throws
 */
function add_source(string $url, string $series, string $slug, string $file_name, string $file_source = SOURCE_FILE, string $namespace = XMLNS_SOURCE_FILE): bool
{
    $dom = load_xml($file_source);
    $root = $dom->documentElement;

    $element = $dom->createElementNS(XMLNS_SOURCE_FILE, 'source');

    //Ajout des attributs
    $element->setAttribute('url', $url);
    $element->setAttribute('series', $series);
    $element->setAttribute('series', $series);
    $element->setAttribute('name', $file_name);
    $element->setAttribute('label', format_to_label($file_name));

    $root->appendChild($element);

    return $dom->save(SOURCE_FILE);
}


/**
 * Ajoute une vidéo source au fichier source si elle n'existe pas déjà
 * @param string $url L'URL de la vidéo source
 * @param string $series La série a laquelle appartient la vidéo source
 * @param string $slug L'identifiant appairaissant dans le nom du fichier de la vidéo source
 * @param string $file_name_saved Le nom du fichier téléchargé (enregistré par youtube-dl)
 * @param string $extension L'extension de la vidéo source
 * @throws Exception - Si le nom du fichier enregistré ne correspond pas aux métadonnées de la source à déclarer
 * @throws Exception - Si le fichier enregistré n'est pas un fichier vidéo valide
 * @throws Exception - Si la déclaration dans le fichier source n'a pas pu être enregistrée
 */
function declare_source(string $url, string $series, string $slug, string $file_name_saved, string $extension = EXTENSION_SOURCE): bool
{

    if (format_to_source_file_raw($series, $slug) !== basename($file_name_saved)) {
        throw new Exception("Le nom du fichier vidéo enregistré ne correspond pas aux métadonnées");
    }

    //A faire : on devrait check avec ffprobe ici que c'est un fichier vidéo valide (et non un simple fichier lambda).
    if (!source_exists(basename($file_name_saved))) {
        throw new Exception("Impossible de déclarer une source, car le fichier vidéo est introuvable dans le dossier sources");
    }

    $result = add_source($url, $series, $slug, basename($file_name_saved));

    if (!$result) {
        throw new Exception("La vidéo source n'a pas pu être déclarée et enregistrée.");
    }

    return $result;
}
