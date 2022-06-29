<?php

/**
 * Header (début de toute réponse de template)
 *
 * @package wsl
 */

declare(strict_types=1);
require_once __DIR__ . '../../../../handlers.php';
require_once __DIR__ . '../../../utils.php';
require_once __DIR__ . '../../../session.php';
autoload();
?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e(site_title()); ?></title>
    <meta name="description" content="Un site collaboratif pour créer et diffuser des ressources vidéos et bibliographiques sur la bureaulogie.">
    <meta name="keywords" content="ackboo, archives, bureaulogie, le tribunal des bureaux, archives, archives de bureaulogie, extraits, ressources bibliographiques">
    <meta name="author" content="websealevel">
    <meta name="author" content="kerprimaparte">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link rel="canonical" href="">
    <!-- Minified version -->
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>

    <div id="page">
        <a href="#content" class="screen-reader-text">
            <?php esc_html_e('Aller au contenu'); ?>
        </a>

        <header id="masthead" class="site-header">

            <div class="site-branding">

                <a href="<?php esc_html_e(home_rel_url()); ?>">
                    <h1 class="site-title">
                        <?php esc_html_e(site_title()); ?>
                    </h1>
                </a>
                <small>
                    Contribuez aux collections publiques d'archives vidéos et de ressources bibliographiques de bureaulogie
                </small>
            </div>
            <nav id="site-navigation">
            </nav>
        </header>

        <div id="content" class="site-content">
            <?php esc_html_breadcrumbs('/'); ?>
            <?php esc_html_notices_e(); ?>