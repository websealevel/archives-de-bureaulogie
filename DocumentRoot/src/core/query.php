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
 * Retourne la liste des extraits attachés à une source, une liste vide si la source n'est pas trouvée.
 * @param string $source_filename Le nom du fichier de la vidéo source
 * @return DOMNodeList
 */
function query_declared_clips_of(string $source_filename): DOMNodeList
{
    if (empty($source_filename))
        return new DOMNodeList();

    $source = query_source_by_unique_attr('name', $source_filename);

    if (false === $source)
        return new DOMNodeList();

    if (!$source->hasChildNodes())
        return new DOMNodeList();

    $clips =  $source->childNodes;

    return $clips;
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
 * Ajoute un extrait au fichier la source s'il n'existe pas déjà. Retourne le noeud ajouté en cas de succès, faux en cas d'échec
 * @param string $source_name Le nom (attr) de la source dont l'extrait est issu
 * @param string $timecode_start
 * @param string $timecode_end
 * @param string $title
 * @param string $description
 * @param string $author
 * @param string $created_on
 * @return DOMNode L'élement extrait ajouté
 * @throws Exception Si l'élément sources est introuvable 
 * @throws Exception Si l'insertion du noeud extrait dans le noeud source échoue
 * @throws Exception Si l'insertion produit un document non valide aux yeux du DTD
 * @throws Exception Si l'enregistrement du fichier source mis à jour échoue
 */
function declare_clip(string $source_name, string $timecode_start, string $timecode_end, string $title, string $description, string $author, string $created_on, string $file_source = SOURCE_FILE): DOMNode
{

    //Validation du format
    build_clip_name($source_name, $timecode_start, $timecode_end);

    $dom = load_xml($file_source);

    //Création de l'extrait et de tous ses éléments enfants.
    $extrait = $dom->createElementNS(XMLNS_SOURCE_FILE, 'extrait');

    $extrait->setAttribute('nb_post', 0);

    if (current_user_can('add_moderator'))
        $extrait->setAttribute('statut', 'publie');
    else
        $extrait->setAttribute('statut', 'en_attente_de_moderation');

    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'title', $title)
    );
    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'description', $description)
    );
    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'debut', $timecode_start)
    );
    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'fin', $timecode_end)
    );
    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'auteur', $author)
    );
    $extrait->appendChild(
        $dom->createElementNS(XMLNS_SOURCE_FILE, 'cree_le', $created_on)
    );

    //Ajouter l'extrait à la source parente
    $sources = $dom->getElementsByTagNameNS(XMLNS_SOURCE_FILE, 'source');
    $parent_source = find_element_by_attribute($sources, 'name', $source_name);

    if (false === $parent_source) {
        throw new Exception("L'extrait n'a pas pu être réalisé car la vidéo source n'est plus enregistrée dans nos archives.");
    }

    $node_added = $parent_source->appendChild($extrait);

    if (!($node_added instanceof DOMNode))
        throw new Exception("Une erreur est survenue lors de l'enregistrement de l'extrait dans nos archives. Veuillez réessayez s'il vous plaît.");

    //Valider le DTD
    try {

        // validate() lance une exception.
        $is_valid = $dom->validate();

        if (false === $is_valid) {
            throw new Exception("L'extrait n'a pas pu être réalisé car ses métadonnées ne nous autorisent pas à l'enregistrer dans nos archives.");
        }
    } catch (Exception $e) {
        write_log($e);
        throw new Exception("L'extrait n'a pas pu être réalisé car ses métadonnées ne nous autorisent pas à l'enregistrer dans nos archives.");
    }

    //Enregistrer
    $result = $dom->save(SOURCE_FILE);

    if (false === $result)
        throw new Exception("Une erreur est survenue lors de l'enregistrement de l'extrait dans nos archives. Veuillez réessayez s'il vous plaît.");

    return $node_added;
}

/**
 * Remarque : voir si on peut optimiser ces itérations avec des fonctions natives. La je veux juste que ça avance.
 * Retourne le premier element d'une liste de nodes dont l'attribut a une certaine valeur
 * @param DOMNodeList $nodes La liste de nodes dans laquelle chercher
 * @param string $attr Le nom de l'attribut sur lequel chercher
 * @param string $value La valeur de l'attribut à chercher
 * @return DOMNode|false
 */
function find_element_by_attribute(DOMNodeList $nodes, string $attr, string $value): DOMNode|false
{
    if (0 === count($nodes))
        return false;

    $node = $nodes->item(0);

    do {
        foreach ($node->attributes as $attr_name => $dom_attr) {
            if ($attr === $attr_name && $value === $dom_attr->value) {
                return $node;
            }
        }
    } while ($node = $node->nextSibling);

    return false;
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


/**
 * Retourne l'élément source de l'extrait
 * @param DOMElement $clip  Optional. L'élément clip 
 * @return DOMElement L'élément source parent
 */
function declared_source_of(DOMElement $clip): DOMElement
{
    return $clip->parentNode;
}

/**
 * Retourne le nom (attribut) de la source déclarée
 * @param DOMElement $source
 * @return string
 */
function source_name(DOMElement $source): string
{
    return $source->getAttribute('name');
}

/**
 * Retourne un élément enfant d'un élément par nom d'élément. Nous sommes *certains* d'avoir l'élément enfant recherché grâce au schéma de validation DTD. Si ce n'est pas le cas, c'est une erreur du développeur.
 * @param DOMEelement $el L'élément dont on cherche un enfant
 * @param string $child_name Le nom de l'élément recherché
 * @return DOMElement L'élément enfant dont le nom correspond
 * @throws Exception Si $child_name est vide, si l'élément n'a pas d'enfants, si un enfant n'est pas défini
 */
function child_element_by_name(DOMElement $el, string $child_name): DOMElement
{

    if (empty($child_name))
        throw new Exception("L'élément " . $child_name . "n'existe pas.");

    if (!$el->hasChildNodes())
        throw new Exception("L'élément " . $el->nodeName . "n'as pas d'éléments enfants.");

    $childs =  $el->childNodes;

    $child = $childs->item(0);

    if (!isset($child))
        throw new Exception("Le premier enfant de " . $el->nodeName . " n'est pas défini");
    do {
        if ($child->nodeName === $child_name) {
            return $child;
        }
    } while ($child = $child->nextSibling);

    throw new Exception("L'élément enfant " . $child_name . "n'existe pas !");
}
