# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*

- [out-of-context-ackboo-twitter-bot](#out-of-context-ackboo-twitter-bot)
  - [Projet](#projet)
    - [Cahier des charges pour l'encodage vidéo/audio des extraits](#cahier-des-charges-pour-lencodage-vidéoaudio-des-extraits)
  - [Architecture générale](#architecture-générale)
    - [Le *fichier source*](#le-fichier-source)
    - [Les différents composants](#les-différents-composants)
  - [Utilisation](#utilisation)
    - [1. Uploader une nouvelle vidéo *source* sur le serveur](#1-uploader-une-nouvelle-vidéo-source-sur-le-serveur)
      - [Manuelle [DEPRECATED]](#manuelle-deprecated)
      - [Via l'appli web](#via-lappli-web)
    - [4.1 Ajouter ou supprimer un extrait manuellement [DEPRECATED]](#41-ajouter-ou-supprimer-un-extrait-manuellement-deprecated)
      - [Ajouter un extrait](#ajouter-un-extrait)
      - [Supprimer un extrait](#supprimer-un-extrait)
    - [4.2 Ajouter, supprimer, modifier un extrait via l'application web](#42-ajouter-supprimer-modifier-un-extrait-via-lapplication-web)
    - [5. Gestion du compte du Twitter Bot](#5-gestion-du-compte-du-twitter-bot)
  - [Formattage des noms](#formattage-des-noms)
    - [Fichier vidéo *source*](#fichier-vidéo-source)
    - [Fichier vidéo *extrait*](#fichier-vidéo-extrait)
    - [Timecode](#timecode)
  - [Gestion des fichiers `downloads`, `sources` et `extraits`](#gestion-des-fichiers-downloads-sources-et-extraits)
    - [Fichier `download`](#fichier-download)
    - [Fichier `source`](#fichier-source)
    - [Fichier `extrait`](#fichier-extrait)
  - [Interface graphique de *cut*](#interface-graphique-de-cut)
    - [Pourquoi ?](#pourquoi-)
    - [Besoins identifiés](#besoins-identifiés)
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

### Cahier des charges pour l'encodage vidéo/audio des extraits

Chaque extrait sera embarqué dans un tweet. Il faut donc veiller à obtenir un bon format et un bon rapport qualité/poids (son et vidéo). Quelques Mo pour un extrait de 2min par exemple.

Après une phase de tests on retiendra les paramètres d'encodage avec les valeurs suivantes

- piste vidéo
  -  résolution max : 720p [x]
  -  format: mp4 [x]
  -  frame rate max : 30fps [x]
  -  video bitrate : 369 kbps [x]
- piste audio
  - data-transfer rate : 96 kbps [x]
  - audio bitrate/échantillonage : 48000 Hz(standard dans un fichier vidéo)  [x]
- cut à la milliseconde [x]

Ces paramètres s'appliquent au téléchargement des vidéos sources et à l'encodage des extraits générés par l'application.

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

## Utilisation

### 1. Uploader une nouvelle vidéo *source* sur le serveur

Si vous souhaitez ajouter une vidéo source à la banque pour en faire de nouveaux extraits.

#### Manuelle [DEPRECATED]
Pousser les vidéos sources (vidéos originales et complètes téléchargées depuis youtube) sur le serveur via le protocole FTP dans le dossier `sources`, en utilisant [Filezilla](https://filezilla-project.org/).

#### Via l'appli web

Faites le via l'appli web (droit nécessaire).

### 4.1 Ajouter ou supprimer un extrait manuellement [DEPRECATED]

#### Ajouter un extrait

Ouvrir le fichier source `extraits.xml`.

Ajouter un élément `extrait` (copier-coller un extrait existant pour gagner du temps mais n'oubliez pas de l'éditer entièrement) **dans l'élément `source` parent dont sera tiré l'extrait**.

Renseigner dans chaque balise correspondante

- `slug` : le [slug](extraits/README.md#slug)
- `description` : une description courte de l'extrait (ou un titre). Elle servira de contenu pour le tweet du bot
- `debut` : le timecode du début. Voir [ici](#timecode) pour le format du timecode
- `fin` : le timecode de fin. Voir [ici](#timecode) pour le format du timecode

L'attribut `utilise` compte le nombre de fois où l'extrait a été twitté par le bot. L'initialiser à `0` par défaut.

L'extrait **doit faire au moins une seconde**.

Par exemple

~~~xml
<source name="le-tribunal-des-bureaux-1.mp4">
</source>
<source name="le-tribunal-des-bureaux-2.mp4">
        <extrait utilise="0">
            <slug>plante-et-luminaire</slug>
            <description>Plantes et luminaires !</description>
            <debut>00.08.27.300</debut>
            <fin>00.09.46.600</fin>
        </extrait>
</source>
~~~

Dans cet exemple, la vidéo source `le-tribunal-des-bureaux-1.mp4` n'a pas encore d'extraits. L'extrait `Plantes et luminaires !` sera extrait de la vidéo source `le-tribunal-des-bureaux-2.mp4`.

#### Supprimer un extrait

Il suffit de supprimer l'élément `<extrait>...</extrait>` correspondant. Si on reprend l'exemple précédent après suppression

~~~xml
<source name="le-tribunal-des-bureaux-1.mp4">
</source>
<source name="le-tribunal-des-bureaux-2.mp4">
</source>
~~~

L'extrait `Plantes et luminaires !` a été retiré de la source `le-tribunal-des-bureaux-2.mp4`. Au prochain passage du programme l'extrait sera supprimé du dossier `extraits`.

### 4.2 Ajouter, supprimer, modifier un extrait via l'application web

A venir...

### 5. Gestion du compte du Twitter Bot

A venir...

## Formattage des noms

### Fichier vidéo *source*

Voir [ici](sources/README.md)

### Fichier vidéo *extrait*

Voir [ici](extraits/README.md)

### Timecode

Les timecodes (instant de début ou de fin de l'extrait) doivent être formattés au format `hh.mm.ss.lll` avec 

- `h` l'heure
- `m` la minute
- `s` la seconde
- `l` la miliseconde

Ils doivent être compris entre `00.00.00.000` et la durée totale de la vidéo.

## Gestion des fichiers `downloads`, `sources` et `extraits`

### Fichier `download`

Quand une nouvelle vidéo est téléchargée via l'application elle n'est pas automatiquement ajoutée aux `sources`. Elle est enregistrée dans le dossier `downloads`. Elle doit être explicitement approuvée par un administrateur pour devenir une *source*.

### Fichier `source`

Les fichiers *sources* sont les vidéos téléchargées depuis youtube entières et servent de source aux extraits. Elles se trouvent dans le dossier `sources`.

Les fichiers *sources* **doivent respecter [un format](sources/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

### Fichier `extrait`

Les *extraits* sont les extraits vidéos des [sources](#fichiers-sources). Ils sont générés automatiquement à partir des informations fournies dans le [fichier source](#le-fichier-source). Ils se trouvent dans le dossier `extraits`.

Les fichiers *extraits* **doivent respecter [un format](extraits/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

Un extrait doit faire **au moins 1 seconde**, sinon il ne sera pas généré et une exception sera levée.

## Interface graphique de *cut*

### Pourquoi ?

Le développement d'une petite application web en surcouche de l'édition du fichier source parait rapidement indispensable pour plusieurs raisons:

- éviter de mauvaises manipulations sur le fichier source
- faciliter l'ajout d'extrait sans erreur
- prévisualiser l'extrait avant de l'enregistrer (un bon cut se fait à la miliseconde)
- pouvoir visionner la vidéo sur la page où on ajuste les timecodes pour éditer son cut de manière plus agréable

### Besoins identifiés

- preview de la vidéo source
- indique le timecode a tout moment
- timecode entrée et de sortie éditables
- manipuler les timecodes via une interface graphique sur le player (luxe)
- plusieurs cut dans un seul fichier via des marqueurs avec label des extraits, en un clic exporter tous les marqueurs (luxe)
- *normaliser le volume* avec une valeur par défaut (voir ré-encodage) au post-montage. Le volume de la piste audio est défini par défaut par une métadonnée. Il faut que la normalisation se fasse sur le cut et non sur la vidéo entière (normalisation est une fonction de la piste entière, analyse les pics/creux de volume sur tout le volume et essaie de normaliser à partir de ça. Donc attention à ça)

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

Suivez les instructions ici pour mettre en place le reverse-proxy sur votre machine, ou modifiez le docker-compose à votre convenance pour associer vos ports aux différents conteneurs.

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