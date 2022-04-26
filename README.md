# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*

## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md).

## Projet

Créer un bot twitter qui *post* des courts extraits vidéos d’ackboo dans une grammaire *out of context*.

### Questions préalables

- comment une vidéo démarre auto dans un tweet ? Il faut embarquer le fichier directement, les liens embed ça marche pas. Donc télécharger la vidéo et l'uploader dans un tweet
- comment un lien youtube est lu de manière intégrée dans un tweet ? Il a pas l'air d'être lu, c'est du texte
- quel format vidéo le plus adapté pour Twitter (rapport qualtié/poids) ? du mp4 au format 720p et 60fps, son à 128kbps voire 96kbps

## Téléchargement de vidéos youtube depuis un server mutualisé

Cela pose problème

- je n'ai pas tous les droits sur le serveur pour installer ce que je veux (comme le wrapper youtube-download)
- youtube change regulièrement d'api donc c'est compliqué de développer un script pour télécharger une vidéo youtube sans qu'il soit difficile à maintenir
- les librairies existantes souffrent toutes du point précédent

Le plus simple serait de télécharger manuellement les vidéos d'ackboo (il en sort pas une par jour non plus j'imagine).

## Formattage vidéo des extraits

- vidéo en 720p en .mp4, 60fps
- son à 128 voire 96 kbps
- cut a la miliseconde nécessaire

## Architecture

On a un fichier texte source **simple à éditer** qui **déclare** les extraits choisis. Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timestamps début et fin de l’extrait.

Le projet a l’architecture suivante :

- fichier source au format XML (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis
- [BONUS, à voir si on en a besoin] un petit programme client web qui sert d'interface utilisateur pour alimenter le fichier source sans le manipuler directement. Il proposera notamment de preview l'extrait avant de l'ajouter.
- un programme se charge de lire le fichier texte et scanner tous les extraits. S'il détecte un extrait retiré, il supprime l'extrait de la base, si nouvel extrait il s'occupe de l'ajouter à la base. [BONUS, à voir si on en a besoin] Une petite base de données est ensuite mise à jour et permet de maintenir le dépôt des extraits, d'ajouter de la logique supplémentaire (par exemple indiquer si tel extrait a déjà été posté pour éviter les doublons). **On peut aussi imaginer faire ça directement dans le XML sous forme d'attribut** (on va pas se faire chier avec une base de données, le XML fera le taff).

- un deuxième programme *post* à une fréquence donnée des extraits issus de cette base de données (le twitter bot)

## Première itération

Au plus simple

### 1. Setup : les données sources et les extraits

- les vidéos complètes (*sources*) sont téléchargées à la main et uploadée sur le serveur
- pour ajouter/retirer un extrait on manipule directement le fichier source `extraits.xml`. Le fichier source est au format XML et stocke l'intégralité des extraits choisis selon un format défini. Editer directement le fichier `extraits.xml` en respectant le schéma de données.
- si le fichier comporte des erreurs après édition, il ne sera pas traité par le programme en charge d'éditer les extraits

### 1.1 Navigation parmi les sources et les extraits

On peut également voir sur le serveur : 

- la liste complète des vidéos sources téléchargées à la main
- la liste des extraits (avec leur nom)

### 2. Génération automatatique des extraits

- un programme utilise le fichier source `extraits.xml` et les vidéos complètes (sources) pour générér/supprimer les extraits déclarés.
- ce programme se déclenche à chaque modification du fichier source
- les extraits sont stockés dans le dossier `extraits`


### 3. Le twitter bot : post automatique d'extraits de manière aléatoire

- un programme twitter bot (derrière un compte) poste à une fréquence donnée (chaque jour, toutes les 12h etc) un extrait pris au hasard dans le dossier `extraits`.


## Usage

- Une personne en charge de pousser les vidéos sources (vidéos completes) sur le serveur via le protocole ftp dans le dossier `sources`
- formatter le nom de la video comme `{nom-serie}-{numero-video|code}-{timestamp-start}-{timestamp-end}.mp4`, tout en [snake-case](https://fr.wikipedia.org/wiki/Snake_case).
- gestion du compte twitter du bot


## Outils nécessaires 

### Télécharger les vidéos sources depuis youtube

Télécharger les vidéos depuis youtube avec [jdownloader](https://jdownloader.org/) 

### Uploader les vidéos sources sur le serveur

Installer [Filezilla](https://filezilla-project.org/) et se connecter avec les identifiants procurés. Déposer les sources dans le dossier `sources` à la racine du projet.

## Ressources

- [Twitter API]()
- [Rate Limits de la Twitter API]()
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [Youtube clips features](https://www.youtube.com/watch?v=A63imEmP_-I)
- [YouTube Video Downloader Script in PHP](https://www.phpzag.com/php-youtube-video-downloader-script/)
- [jdownloader](https://jdownloader.org/), outil open-source de téléchargement en flux continu
- [filezilla](https://filezilla-project.org/), client ftp open-source