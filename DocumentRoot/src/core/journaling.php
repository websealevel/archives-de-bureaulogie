<?php

/**
 * Les fonctions de log (dev & prod) et de production de rapports
 *
 * @package wsl 
 */


require_once __DIR__ . '/const.php';

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
 * Affiche sur la sortie standard ou dans un fichier de log (en fonction du mode de php utilisé) le rapport de la génération des clips
 * @see generate_clips_report()
 * @param array $report Le rapport obtenu par la génération des clips
 * @return void
 */
function report_generate_clips_e(array $report)
{

    $output = array();

    $already_exists = $report['already_exists'] ?? array();
    $created = $report['created'] ?? array();
    $invalid = $report['invalid'] ?? array();

    $output[] = "==> Rapport de génération de clips : DEBUT " . date('d-m-Y H:m:s') . " == \n";

    $output[] = sprintf("Extrait(s) crée(s): %d\n", count($created));

    foreach ($created as $clip) {
        $output[] = sprintf("* %s\n", $clip);
    }
    $output[] = sprintf("Extrait(s) déjà existant(s): %d\n", count($already_exists));

    foreach ($already_exists as $clip) {
        $output[] = sprintf("* %s\n", $clip);
    }

    $output[] = sprintf("Extrait(s) invalide(s): %d\n", count($invalid));

    foreach ($invalid as $clip) {
        $output[] = sprintf("* %s\n", $clip);
    }

    $output[] = "<==\n\n";

    print_report($output);
}


/**
 * Ecrit le tableau $output ligne par ligne sur la sortie standard si php est en mode CLI, dans le fichier de log du core si php est en mode CGI
 * @param array $output Les données à imprimer
 * @return void
 */
function print_report(array $output): void
{
    if (is_cli()) {
        foreach ($output as $line) {
            echo $line . PHP_EOL;
        }
    } else {
        $file = core_reporting_file();
        if (!is_file($file)) {
            $path = __DIR__ . '/' . $file;
            file_put_contents($path, $output);
        }
        file_put_contents($path, $output, FILE_APPEND);
    }
    return;
}

/**
 * Affiche sur la sortie standard ou dans un fichier de log (en fonction du mode de php utilisé) le rapport de nettoyage des clips
 * @see generate_clips_report()
 * @param array $report Le rapport obtenu par le nettoyage des clips
 * @return void
 */
function report_clean_clips_e(array $report)
{

    $output = array();
    $output[] = "==> Rapport de nettoyage de clips : DEBUT . Date : " . date('d-m-Y H:m:s') . " == \n";

    $output[] = "<== Rapport de nettoyage de clips : FIN == \n\n";

    print_report($output);
}

/**
 * Retourne le fichier où sont enregistrés les rapports du composant core de l'application (où sont écrits les rapports)
 */
function core_reporting_file(): string
{
    return 'core_journal.log';
}
