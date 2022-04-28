<?php

/**
 * Les fonctions de log (dev & prod) et de production de rapports
 *
 * @package wsl 
 */


require_once 'const.php';

/**
 * Retourne un rapport sur la génération d'extraits
 * @see generate_clips()
 * @return array Un tableau de rapport
 */
function report_clip_generation(): array
{
    //Produit un rapport sur génération de clips : liste des extraits générés, erreurs éventuelles. A peaufiner
    return array(
        'Clips générés avec succès !'
    );
}

/**
 * Log un rapport sur la génération des extraits
 * @param array Un tableau contenants des entrées d'un rapport
 * @see report_clip_generation()
 */
function log_clip_generation(array $report)
{
    foreach ($report as $entry) {
        error_log($entry);
    }
}

/**
 * Retourne un rapport sur l'état des sources/extraits déclarés et présents sur le serveur
 * @param string $file_source Optional. Défaut 'extraits.xml' Le fichier source contenant la déclaration des extraits
 * @return array Rapport
 */
function report(string $file_source = SOURCE_FILE): array
{
    $declared_sources = query_declared_sources($file_source);
    $declared_clips = query_declared_clips($file_source);
    $sources = query_sources();
    $clips = query_clips();

    return array(
        'declared' => array(
            'sources' => array(
                'quantity' => 'Nombre de sources déclarées : ' . $declared_sources->count(),
                'list' => iterator_to_array($declared_sources)
            ),
            'clips' => array(
                'quantity' => 'Nombre d\'extraits déclarés :' . $declared_clips->count(),
                'list' => iterator_to_array($declared_clips)
            )
        ),
        'files' => array(
            'sources' => array(
                'quantity' => 'Nombre de sources :' . count($sources),
                'list' => $sources
            ),
            'clips' => array(
                'quantity' => 'Nombre d\'extraits :' . count($clips),
                'list' => $clips
            )
        )
    );
}

/**
 * Affiche sur la sortie standard le rapport de la génération des clips
 * @see generate_clips_report()
 * @param array $report Le rapport obtenu par la génération des clips
 * @return void
 */
function generate_clips_report_e(array $report)
{

    $already_exists = $report['already_exists'] ?? array();
    $created = $report['created'] ?? array();

    printf("La base de données des extraits a été mise à jour\n");

    printf("Extrait(s) crée(s): %d\n", count($created));
    foreach ($created as $clip) {
        printf("* %s\n", $clip);
    }

    printf("Extrait(s) déjà existant(s): %d\n", count($already_exists));
    foreach ($already_exists as $clip) {
        printf("* %s\n", $clip);
    }
}
