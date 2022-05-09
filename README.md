# Archives de bureaulogie

*Les archives de bureaulogie* est un projet qui a pour ambition de promouvoir la bureaulogie en proposant une plateforme collaborative de publication d'extraits vidéos du [tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p) d'ackboo (père fondateur de la bureaulogie) ainsi que de diffusion de références bibliographiques pour approfondir le sujet.

Il sert également de prétexte pour construire un outil en *vanilla php* pour poursuivre la formation.

- [Archives de bureaulogie](#archives-de-bureaulogie)
  - [Projet](#projet)
  - [*Getting started*](#getting-started)
    - [Prérequis](#prérequis)
    - [Lancer le projet](#lancer-le-projet)
    - [Arrêter le projet](#arrêter-le-projet)
    - [Réinitialiser la base de données](#réinitialiser-la-base-de-données)
  - [Ressources](#ressources)

<!-- ## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md). -->

## Projet

Voir les specifications techniques du projet [ici](backlog.md).

## *Getting started*

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

Suivez [les instructions ici](https://github.com/websealevel/local-env-docker) pour mettre en place le reverse-proxy sur votre machine, ou modifiez le `docker-compose` à votre convenance pour associer vos ports aux différents conteneurs.

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
- [jdownloader](https://jdownloader.org/), outil open-source de téléchargement en flux continu
- [FFMPEG, Invoking command-line tools from PHP scripts](https://trac.ffmpeg.org/wiki/PHP)
- [norkunas/youtube-dl-php ](https://packagist.org/packages/norkunas/youtube-dl-php), wrapper php pour youtube-dl
- [youtube-dl](https://github.com/ytdl-org/youtube-dl), download videos from youtube.com or other video platforms 
- [lostlesscut](https://github.com/mifi/lossless-cut), le couteau suisse de l'édition vidéo/audio pour cut la vidéo d'origine
