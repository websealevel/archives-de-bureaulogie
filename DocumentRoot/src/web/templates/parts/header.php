<?php

/**
 * Header
 *
 * @package wsl
 */

declare(strict_types=1);
require_once __DIR__ . '../../../../../../vendor/autoload.php';
require_once __DIR__ . '../../../../handlers.php';
require_once __DIR__ . '../../../utils.php';

?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php esc_html_e(site_title()); ?></title>
    <meta name="description" content="Un Twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux">
    <meta name="keywords" content="ackboo, bot Twitter, clips">
    <meta name="author" content="websealevel">
    <meta name="author" content="kerprimaparte">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link rel="canonical" href="ackerboooutofcontext.websealevel.com">
    <link rel="stylesheet" href="../assets/css/main.css">
    <code></code>
</head>

<body>


    <div id="page">
        <a href="#content" class="screen-reader-text">
            <?php esc_html_e('Aller au contenu'); ?>
        </a>

        <header id="masthead" class="site-header">

            <div class="site-branding">
                <p class="site-title">
                    <a href="<?php esc_html_e(site_url()); ?>">
                        <h1>
                            <?php esc_html_e(site_title()); ?>
                        </h1>
                    </a>
                </p>
                <p class="site-description">
                </p>
            </div>
            <nav id="site-navigation">
            </nav>
        </header>

        <div id="content" class="site-content">