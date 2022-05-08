# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'ackboo issus de sa série "Le tribunal des bureaux"*

- [out-of-context-ackboo-twitter-bot](#out-of-context-ackboo-twitter-bot)
  - [Projet](#projet)
  - [Architecture générale](#architecture-générale)
    - [Le *fichier source*](#le-fichier-source)
    - [Les différents composants](#les-différents-composants)
  - [Getting started](#getting-started)
    - [Prérequis](#prérequis)
    - [Lancer le projet](#lancer-le-projet)
    - [Arrêter le projet](#arrêter-le-projet)
    - [Réinitialiser la base de données](#réinitialiser-la-base-de-données)
  - [Ressources](#ressources)

<!-- ## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md). -->

## Projet

Créer un bot twitter qui *post* des courts extraits vidéos d’ackboo dans une grammaire *out of context*.


## Architecture générale

### Le *fichier source*

La base de données des extraits est gérée par le `fichier source`. Le fichier source est `extraits.xml`. Il contient tout le travail éditorial de déclaration des extraits. Ce fichier est manipulé par différents programmes (ou à la main mais prudence !) pour gérer les extraits (création, modification, suppression).

Ce fichier est **simple à éditer** et il **déclare** les extraits choisis. Il fait office de *source de vérité* et il définit l'état de la base de données d'extraits (quels extraits sont présents ou non). Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timecodes début et fin de l’extrait (c) d'un slug (d) d'une description.

### Les différents composants

Le projet a l’architecture suivante :

- *fichier source* au format XML (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis. Il est *la base de données des extraits*.
- un programme client web qui sert d'interface utilisateur pour éditer les extraits (ajouter, modifier, supprimer)
- un programme qui se charge de lire/manipuler le fichier source pour créer/supprimer/modifier les extraits
- un deuxième programme qui *post* à une fréquence donnée des extraits issus de cette base de données (aka le Twitter Bot)

## Getting started

### Prérequis

Installer

- [composer](https://getcomposer.org/)
- [docker](https://www.docker.com/)
- [docker-compose](https://docs.docker.com/compose/)
  
Installer ffmpeg

~~~bash
wget https://johnvansickle.com/ffmpeg/builds/ffmpeg-git-amd64-static.tar.xz -O ffmpeg.tar.xz
tar -xzvf ffmpeg.tar.xz
~~~

Installer youtube-dl

~~~bash
wget https://yt-dl.org/downloads/latest/youtube-dl -O youtube-dl
~~~

Mettre à jour les dépendances du projet

~~~bash
composer update
~~~

### Lancer le projet

Suivez [les instructions ici](https://github.com/websealevel/local-env-docker) pour mettre en place le reverse-proxy sur votre machine, ou modifiez le docker-compose à votre convenance pour associer vos ports aux différents conteneurs.

Pour lancer le projet

~~~bash
docker-compose up -d
~~~

### Arrêter le projet

~~~bash
docker-compose down
~~~

### Réinitialiser la base de données 

Le script `docker_postgres_init.sql` est executé par le conteneur de de postgresql au premier lancement. 

Pour réinitialiser la base et ré excuter le script, arrêter le projet, supprimez le dossier `postgres-data`, puis relancer le projet

~~~bash
docker-compose down
sudo rm -R postgres-data
docker-compose up -d
~~~

## Ressources

- [Twitter API](https://developer.twitter.com/en/docs/twitter-api)
- [Rate Limits de la Twitter API](https://developer.twitter.com/en/docs/twitter-api/rate-limits)
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [Youtube clips features](https://www.youtube.com/watch?v=A63imEmP_-I)
- [YouTube Video Downloader Script in PHP](https://www.phpzag.com/php-youtube-video-downloader-script/)
- [jdownloader](https://jdownloader.org/), outil open-source de téléchargement en flux continu
- [filezilla](https://filezilla-project.org/), client ftp open-source
- [lostlesscut](https://github.com/mifi/lossless-cut), le couteau suisse de l'édition vidéo/audio pour cut la vidéo d'origine
- [FFMPEG, Invoking command-line tools from PHP scripts](https://trac.ffmpeg.org/wiki/PHP)
- [norkunas/youtube-dl-php ](https://packagist.org/packages/norkunas/youtube-dl-php), wrapper php pour youtube-dl
- [youtube-dl](https://github.com/ytdl-org/youtube-dl), download videos from youtube.com or other video platforms 