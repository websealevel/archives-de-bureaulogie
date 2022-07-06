# Archives de bureaulogie

Les [archives de bureaulogie](https://archives-de-bureaulogie.fr) est un projet qui a pour ambition de promouvoir la bureaulogie en proposant une plateforme collaborative de publication d'extraits vidéos du [tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p) d'ackboo (père fondateur de la bureaulogie) ainsi que de diffusion de références bibliographiques pour approfondir le sujet.

Il sert également de prétexte pour construire un outil en *vanilla php* pour se former et s'amuser.

- [Archives de bureaulogie](#archives-de-bureaulogie)
  - [A l'intention des utilisateur·trice·s du code](#a-lintention-des-utilisateurtrices-du-code)
    - [*vanilla PHP first*](#vanilla-php-first)
    - [*XML*](#xml)
    - [Backup](#backup)
  - [Projet: vue d'ensemble et suivi](#projet-vue-densemble-et-suivi)
  - [Au délà de la bureaulogie, ce qu'est vraiment le codesource du projet](#au-délà-de-la-bureaulogie-ce-quest-vraiment-le-codesource-du-projet)
  - [Forker le projet pour faire son propre outil d'édition/publication d'extraits vidéos](#forker-le-projet-pour-faire-son-propre-outil-déditionpublication-dextraits-vidéos)
  - [*Getting started*](#getting-started)
    - [Prérequis](#prérequis)
      - [Dépendences dev](#dépendences-dev)
      - [Dépendences prod](#dépendences-prod)
      - [Droits](#droits)
      - [ffmpeg, ffprobe et youtube-dl](#ffmpeg-ffprobe-et-youtube-dl)
    - [Configuration de php-fpm](#configuration-de-php-fpm)
    - [Configuration du virtual host](#configuration-du-virtual-host)
      - [Nginx](#nginx)
      - [Apache](#apache)
    - [Gestion des logs](#gestion-des-logs)
      - [Logs de nginx](#logs-de-nginx)
      - [Logs de php-fpm](#logs-de-php-fpm)
    - [Configuration du projet](#configuration-du-projet)
      - [Installer les dépendances](#installer-les-dépendances)
        - [environnement de dev](#environnement-de-dev)
        - [environnement de prod](#environnement-de-prod)
        - [le fichier `.env`](#le-fichier-env)
    - [Lancer le projet](#lancer-le-projet)
    - [Arrêter le projet](#arrêter-le-projet)
    - [Core functions (CLI)](#core-functions-cli)
      - [checker la validation du fichier source (`extraits.xml`)](#checker-la-validation-du-fichier-source-extraitsxml)
      - [mettre à jour les fichiers clips à partir du fichier source (cree clips déclarés manquants, supprime les fichiers clips invalides et non déclarés)](#mettre-à-jour-les-fichiers-clips-à-partir-du-fichier-source-cree-clips-déclarés-manquants-supprime-les-fichiers-clips-invalides-et-non-déclarés)
    - [FAQ - Problèmes connus, rencontrés](#faq---problèmes-connus-rencontrés)
      - [Comment réinitialiser la base de données ?](#comment-réinitialiser-la-base-de-données-)
      - [Erreur avec la dépendence symfony/process de FFMPEG](#erreur-avec-la-dépendence-symfonyprocess-de-ffmpeg)
        - [Expected Behavior](#expected-behavior)
        - [Steps to Reproduce](#steps-to-reproduce)
        - [Solution](#solution)
      - [Youtube-dl renvoie l'erreur `'ERROR: unable to download video data: HTTP Error 403: Forbidden while using youtube_dl'`](#youtube-dl-renvoie-lerreur-error-unable-to-download-video-data-http-error-403-forbidden-while-using-youtube_dl)
      - [FFMPEG renvoie une erreur `'Encoding Failed'`](#ffmpeg-renvoie-une-erreur-encoding-failed)
  - [Ressources](#ressources)


## A l'intention des utilisateur·trice·s du code


### *vanilla PHP first*
Ce projet a été volontairement développé en *vanilla PHP* pour plusieurs raisons
- apprendre du PHP "bas niveau"
- apprendre des besoins fonctionnels d'une appli web
- mieux comprendre l'intérêt d'un framework
- mieux comprendre l'intérêt des PSR et notamment du PSR-4

**Il est inutile pour le moment de proposer des `PR` sur du refactoring de code** car la version suivante sera entièrement refactorée en suivant le PSR-4. Vous pouvez [voir la roadmap du projet ici](backlog.md#roadmap). Je mettrais également de l'ordre dans [l'architecture du projet](backlog.md#architecture-générale) (séparer la partie core de la partie web).

### *XML*

Ce projet utilise également un fichier XML qui sert de *registre* pour suivre les sources et les extraits. J'avais envie aussi d'en apprendre plus sur le XML d'où ce choix plutôt que d'utiliser la base de données. L'idée est également de pouvoir reconstituer l'intégrale des archives à partir du fichier source `extraits.xml`. Pour cela, la partie `core` [intègre une partie CLI indépendante de l'application web](#core-functions-cli).

### Backup

Un backup du projet web demande donc
- `extraits.xml`
- `extraits.dtd`
- `dump de la base` (comptes)

Un backup du projet core demande seulement
- `extraits.xml`
- `extraits.dtd`

## [Projet: vue d'ensemble et suivi](backlog.md)

Voir les [spécifications techniques et le backlog du projet](backlog.md).

## Au délà de la bureaulogie, ce qu'est vraiment le codesource du projet

A venir...

## Forker le projet pour faire son propre outil d'édition/publication d'extraits vidéos

A venir...

## *Getting started*

### Prérequis

#### Dépendences dev

- [composer](https://getcomposer.org/)
- [docker](https://www.docker.com/)
- [docker-compose](https://docs.docker.com/compose/)
- python
- php8
  

#### Dépendences prod

- [composer](https://getcomposer.org/)
- python
- php8

#### Droits

Vérifier que l'utilisateur qui execute les scripts PHP dispose des droits d'écritures sur les dossiers `extraits` et `sources`.

#### ffmpeg, ffprobe et youtube-dl

Installer `ffmpeg` et `ffprobe` dans le dossier `DocumentRoot/ffmpeg`

Télécharger les builds static [ici](https://ffmpeg.org/download.html#build-linux) (vous pouvez aussi [recompiler le code source](https://ffmpeg.org/download.html#repositories) mais il faudra bien configurer le build pour inclure les codecs comme `x264`, voir `./configure --help` pour plus d'info).

Copier les executables `ffmpeg` et `ffprobe` dans le dossier `DocumentRoot/ffmpeg`.

Installer `youtube-dl` dans le dossier `DocumentRoot/youtube-dl` (youtube-dl a besoin de python3.2+ pour fonctionner).

~~~bash
curl -L https://yt-dl.org/downloads/latest/youtube-dl -o youtube-dl
~~~

### Configuration de php-fpm

On utilise `php-fpm` qui utilise

- `$PHP_INI_DIR/php.ini` pour *php core*
- `$PHP_INI_DIR/php-fpm.conf` comme configuration globale de php-fpm
- `$PHP_INI_DIR/php-fpm.d/www.conf` pour la configuration de chaque pool de process php

avec ici `$PHP_INI_DIR=/usr/local/etc/php`. 

### Configuration du virtual host

Il faut rediriger toutes les reqûetes vers le `index.php` à la racine du projet (le routeur fonctionne ainsi).

#### Nginx

Dans le `nginx.conf` à la racine du projet.

```bash

# deny hidden files and files with the extensions listed below
    location ~ /\.|\.(?:xml|dtd|php|env|json|lock|ini|log)$ {
        deny all;
    }

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location = /index.php {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_pass php-fpm:9000;
    }

```

#### Apache

Dans le `.htaccess` à la racine du projet

```bash

# Deny access to .htaccess
<Files .htaccess>
Order allow,deny
Deny from all
</Files>
<FilesMatch "\.(xml|dtd|env|json|lock|ini|log|sh)$">
Order allow,deny
Deny from all
</FilesMatch>

RewriteEngine on
RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]

```

### Gestion des logs

L'application dispose de plusieurs logs.

#### Logs de nginx

Configurés dans `nginx.conf` : `archives.access.log` et `archives.error.log`.

#### Logs de php-fpm

Il faut configurer le fichier `$PHP_INI_DIR/php-fpm.d/www.conf` et ajouter

~~~ini
catch_workers_output = yes
php_flag[display_errors] = on
php_admin_value[error_log] = /path/du/log/app.log
php_admin_flag[log_errors] = on
~~~

Créer le fichier `/path/du/log/app.log` et donner donner la permission à php d'écrire dessus `touch /path/du/log/app.log && chmod 666 /path/du/log/app.log`.

>php-fpm n'utilise pas la configuration définies dans `php.ini`, utilise `$PHP_INI_DIR/php-fpm.d/www.conf`. Un sujet encore à creuser

### Configuration du projet

#### Installer les dépendances

Mettre à jour les dépendances du projet. Se placer à la racine du projet puis

##### environnement de dev

~~~bash

composer update

~~~

ou

~~~bash

composer install

~~~

##### environnement de prod

`composer install --no-dev --quiet --optimize-autoloader`

##### le fichier `.env`

La configuration du projet se fait dans un fichier `.env` à la racine de `DocumentRoot`. 

~~~bash
mv DocumentRoot/.env.dist DocumentRoot/.env
~~~

Voici les options par défaut. Redéfinissez-les selon vos usages.

```bash

# Database credentials
DB_HOST="db"
DB_NAME="mydb"
DB_PORT="5432"
DB_USER="user"
DB_PASSWORD="password"
DB_CHARSET="utf8"

# FFMPEG
PATH_BIN_FFMPEG=ffmpeg/ffmpeg
PATH_BIN_FFPROBE=ffmpeg/ffprobe
FFMPEG_TIMEOUT=3600
FFMPEG_THREADS=12

# youtube-dl
PATH_PYTHON=/usr/bin/python3
PATH_BIN_YOUTUBEDL=youtube-dl/youtube-dl
```

La liste des options

- `DB_HOST` : le nom d'hôte de la base de données
- `DB_NAME` : le nom de la base de données
- `DB_USER` : le nom de l'utilisateur de la base de données
- `DB_PASSWORD` : le mot de passe de l'utilisateur de la base de données
- `DB_CHARSET` : l'encodage des caractères de la base de données
- `PATH_BIN_FFMPEG`: le chemin **relatif à DocumentRoot** du bin FFMPEG
- `PATH_BIN_FFMPROBE`: le chemin **relatif à DocumentRoot** du bin FFMPROBE
- `FFMPEG_TIMEOUT`: le timeout de FFMPEG
- `FFMPEG_THREADS`: le nombre de threads utilisé par FFMPEG
- `PATH_PYTHON`: le path de python (python3+)
- `PATH_BIN_YOUTUBEDL`: le chemin **relatif à DocumentRoot** de youtube-dl
- `SITE_MAINTENANCE_MODE`: 0 pas en maintenance, 1 en maintenance
- `SITE_DISABLE_SIGN_UP`: 0 les inscriptions sont ouvertes, 1 fermées


### Lancer le projet

Suivez [les instructions ici](https://github.com/websealevel/local-env-docker) pour mettre en place le reverse-proxy sur votre machine, ou modifiez le `docker-compose` à votre convenance pour associer vos ports aux différents conteneurs.

Pour construire le projet (premier lancement)
~~~bash
docker-compose up --build -d
~~~

Pour reconstruire un service, par exemple le service `back`
~~~bash
docker-compose build back
~~~

Pour lancer le projet

~~~bash
docker-compose up -d
~~~

### Arrêter le projet

~~~bash
docker-compose down
~~~

### Core functions (CLI)

Des tâches *core* executables directement depuis la ligne de commande sur le serveur, indépendemment de l'appliaction web.

#### checker la validation du fichier source (`extraits.xml`)
~~~php
php -r "require 'src/core/validation.php'; is_source_file_valid();"
~~~

#### mettre à jour les fichiers clips à partir du fichier source (cree clips déclarés manquants, supprime les fichiers clips invalides et non déclarés)

~~~php
php -r "require 'src/core/actions.php' ; action_update_clips();"
~~~

### FAQ - Problèmes connus, rencontrés

#### Comment réinitialiser la base de données ?

Le script `docker_postgres_init.sql` est executé par le conteneur de de postgresql au premier lancement. 

Pour réinitialiser la base et ré excuter le script, arrêter le projet, supprimez le dossier `postgres-data`, puis relancer le projet

~~~bash
docker-compose down
sudo rm -R postgres-data
docker-compose up -d
~~~

#### Erreur avec la dépendence symfony/process de FFMPEG

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

##### Solution

Ne pas enregistrer de valeur `array` dans `$_ENV` pour corriger le pb (pratique non recommandée).

#### Youtube-dl renvoie l'erreur `'ERROR: unable to download video data: HTTP Error 403: Forbidden while using youtube_dl'`

Il faut supprimer le cache

~~~
/usr/bin/python3 youtube-dl --rm-cache-dir
~~~

#### FFMPEG renvoie une erreur `'Encoding Failed'`

Cette erreur est envoyée au moment de l'écriture du fichier dans le dossier. Il faut s'assurer de donner les droits d'écriture à l'utiliasteur nginx sur le dossier.


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
- [Jquery ajax](https://api.jquery.com/Jquery.ajax/)
- [Docker- Official build of Nginx.](https://hub.docker.com/_/nginx/)
- [nginx documentation](https://nginx.org/en/docs/)
- [nginx beginer's guide](https://nginx.org/en/docs/beginners_guide.html)
- [dockerize-webserver-nginx-php8](https://marcit.eu/en/2021/04/28/dockerize-webserver-nginx-php8/)
- [crowdstar/background-processing](https://github.com/Crowdstar/background-processing), un paquet php assez simple pour gérer des processus en tâche de fond
- *Modern PHP* by Josh Lockhart, O'Reilly, 2015, Chapter 7, *p138-146*
- [mdn web docs : APIs vidéo et audio](https://developer.mozilla.org/fr/docs/Learn/JavaScript/Client-side_web_APIs/Video_and_audio_APIs)
- [Implementer son propre lecteur video HTML5](https://developer.mozilla.org/en-US/docs/Web/API/HTMLVideoElement)
- [AudioVolume](https://trac.ffmpeg.org/wiki/AudioVolume), doc de FFMPEG sur les stratégies de normalization du son
- [Normaliser le son avec ffmpeg](https://superuser.com/questions/323119/how-can-i-normalize-audio-using-ffmpeg), un bon post stackoverflow maitnenu depuis des années, bonne doc et pointage vers [un repo d'un bin python](https://github.com/slhck/ffmpeg-normalize) qui s'occupe de faire la normalization en une passe
- [FFMPEG: filtre loudnorm](http://ffmpeg.org/ffmpeg-all.html#loudnorm), EBU R128 loudness normalization filter.
- [Audio loudness, Google doc](https://developers.google.com/assistant/tools/audio-loudness), chapitre sur ffmpeg et exemple
- [Capistrano](https://capistranorb.com/), un outil de déploiement automatique, simple, prédictible et réversible écrit en Ruby
- [Extension memcached](https://pecl.php.net/package/memcached)