# Archives de bureaulogie

Les [archives de bureaulogie](https://archives-de-bureaulogie.fr) est un projet qui a pour ambition de promouvoir la bureaulogie en proposant une plateforme collaborative de publication d'extraits vidéos du [tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p) d'ackboo (père fondateur de la bureaulogie) ainsi que de diffusion de références bibliographiques pour approfondir le sujet.

Il sert également de prétexte pour construire un outil en *vanilla php* pour se former et s'amuser.

- [Archives de bureaulogie](#archives-de-bureaulogie)
  - [Projet : vue d'ensemble et suivi](#projet--vue-densemble-et-suivi)
  - [*Getting started*](#getting-started)
    - [Prérequis](#prérequis)
      - [Dépendences dev](#dépendences-dev)
      - [ffmpeg et ffprobe](#ffmpeg-et-ffprobe)
      - [youtube-dl](#youtube-dl)
      - [Configuration](#configuration)
- [Database credentials](#database-credentials)
- [FFMPEG](#ffmpeg)
- [youtube-dl](#youtube-dl-1)
    - [Lancer le projet](#lancer-le-projet)
    - [Arrêter le projet](#arrêter-le-projet)
    - [Core functions (CLI)](#core-functions-cli)
      - [checker la validation du fichier source](#checker-la-validation-du-fichier-source)
    - [Réinitialiser la base de données](#réinitialiser-la-base-de-données)
    - [Problèmes connus](#problèmes-connus)
      - [FFMPEG / Symfony](#ffmpeg--symfony)
        - [Expected Behavior](#expected-behavior)
        - [Steps to Reproduce](#steps-to-reproduce)
      - [Youtube-dl renvoie l'erreur 'ERROR: unable to download video data: HTTP Error 403: Forbidden while using youtube_dl'](#youtube-dl-renvoie-lerreur-error-unable-to-download-video-data-http-error-403-forbidden-while-using-youtube_dl)
  - [Ressources](#ressources)

<!-- ## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md). -->

## Projet : vue d'ensemble et suivi

Voir les specifications techniques et le backlog du projet [ici](backlog.md).

## *Getting started*

### Prérequis

#### Dépendences dev
Installer

- [composer](https://getcomposer.org/)
- [docker](https://www.docker.com/)
- [docker-compose](https://docs.docker.com/compose/)
  

#### ffmpeg et ffprobe

Installer `ffmpeg` et `ffprobe` dans le dossier `DocumentRoot/ffmpeg`

Télécharger les builds static `ici` (vous pouvez aussi recompiler le code source mais il faudra bien configurer le build pour inclure les codecs comme x264, voir `./configure --help` pour plus d'info).

Copier les executables `ffmpeg` et `ffprobe` dans le dossier `DocumentRoot/ffmpeg`.

#### youtube-dl

Installer `youtube-dl` dans le dossier `DocumentRoot/youtube-dl` (youtube-dl a besoin de python3.2+ pour fonctionner).

~~~bash
curl -L https://yt-dl.org/downloads/latest/youtube-dl -o youtube-dl
~~~

Mettre à jour les dépendances du projet. Se placer à la racine du projet puis

~~~bash
composer update
~~~

#### Configuration

La configuration du projet se fait dans un fichier `.env` à la racine de `DocumentRoot`

Voici les options par défaut. Surchargez les à votre convenance.

~~~
# Database credentials
DB_HOST="db"
DB_NAME="mydb"
DB_PORT="5432"
DB_USER="user"
DB_PASSWORD="password"
DB_CHARSET="utf8"

# FFMPEG
PATH_BIN_FFMPEG=/var/www/html/ffmpeg/ffmpeg
PATH_BIN_FFPROBE=/var/www/html/ffmpeg/ffprobe
FFMPEG_TIMEOUT=3600
FFMPEG_THREADS=12

# youtube-dl
PATH_PYTHON=/usr/bin/python3
PATH_BIN_YOUTUBEDL=/var/www/html/youtube-dl/youtube-dl
~~~

La liste des options

- `DB_HOST` : le nom d'hôte de la base de données
- `DB_NAME` : le nom de la base de données
- `DB_USER` : le nom de l'utilisateur de la base de données
- `DB_PASSWORD` : le mot de passe de l'utilisateur de la base de données
- `DB_CHARSET` : l'encodage des caractères de la base de données
- `PATH_BIN_FFMPEG`: le chemin du bin FFMPEG
- `PATH_BIN_FFMPROBE`: le chemin du bin FFMPROBE
- `FFMPEG_TIMEOUT`: le timeout de FFMPEG
- `FFMPEG_THREADS`: le nombre de threads utilisé par FFMPEG
- `PATH_PYTHON`: le path de python (python3+)
- `PATH_BIN_YOUTUBEDL`: le path de
- `SITE_MAINTENANCE_MODE`: 0 pas en maintenance, 1 en maintenance
- `SITE_DISABLE_SIGN_UP`: 0 les inscriptions sont ouvertes, 1 fermées

### Lancer le projet

Suivez [les instructions ici](https://github.com/websealevel/local-env-docker) pour mettre en place le reverse-proxy sur votre machine, ou modifiez le `docker-compose` à votre convenance pour associer vos ports aux différents conteneurs.

Pour lancer le projet

~~~bash
docker-compose up -d
~~~

### Arrêter le projet

~~~bash
docker-compose down
~~~

### Core functions (CLI)

#### checker la validation du fichier source
~~~php
php -r "require 'src/core/validation.php'; is_source_file_valid();"
~~~

### Réinitialiser la base de données 

Le script `docker_postgres_init.sql` est executé par le conteneur de de postgresql au premier lancement. 

Pour réinitialiser la base et ré excuter le script, arrêter le projet, supprimez le dossier `postgres-data`, puis relancer le projet

~~~bash
docker-compose down
sudo rm -R postgres-data
docker-compose up -d
~~~

### Problèmes connus

#### FFMPEG / Symfony

Issue ouverte [ici](https://github.com/symfony/symfony/issues/46348).

PHP-FFMpeg (en fait c'est dans `symfony/process`) encounters an unexpected error when a custom array value is registered in super globals `$_ENV`. This custom array value is parsed and generates an error.

##### Expected Behavior

It should not produce an error.

##### Steps to Reproduce

Register an array in `$_ENV` , for example

~~~
$_ENV['foo'] = array('bar');
~~~

Then when the method `start()` in `Process.php` (ligne 293) iterates over the local variable `$env` in the for loop an error occurs.

Ne pas enregistrer de valeur `array` dans `$_ENV` pour corriger le pb en attendant.

#### Youtube-dl renvoie l'erreur 'ERROR: unable to download video data: HTTP Error 403: Forbidden while using youtube_dl'

Il faut supprimer le cache

~~~
/usr/bin/python3 youtube-dl --rm-cache-dir
~~~

## Ressources

- [Twitter API](https://developer.twitter.com/en/docs/twitter-api)
- [Rate Limits de la Twitter API](https://developer.twitter.com/en/docs/twitter-api/rate-limits)
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [jdownloader](https://jdownloader.org/), outil open-source de téléchargement en flux continu
- [FFMPEG, Invoking command-line tools from PHP scripts](https://trac.ffmpeg.org/wiki/PHP)
- [norkunas/youtube-dl-php ](https://packagist.org/packages/norkunas/youtube-dl-php), wrapper php pour youtube-dl
- [youtube-dl](https://github.com/ytdl-org/youtube-dl), download videos from youtube.com or other video platforms 
- [yt-dlp](https://github.com/yt-dlp/yt-dlp#installation), un fork de youtube-dlc, lui même un fork de youtube-dl. Nouvelles features avancées, bien maintenu. Alternative envisageable.
- [lostlesscut](https://github.com/mifi/lossless-cut), le couteau suisse de l'édition vidéo/audio pour cut la vidéo d'origine
