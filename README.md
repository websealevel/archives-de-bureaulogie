# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*

## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md).

## Projet

Créer un bot twitter qui *post* des courts extraits vidéos d’ackboo dans une grammaire *out of context*.

## Architecture

Plusieurs solutions.

Il faut faire d'abord au plus simple et voir à l'usage si y'a des trucs à améliorer, faut pas *overingeniering*

### Questions

- comment une vidéo démarre auto dans un tweet ? Il faut embarquer le fichier directement, les liens embed ça marche pas
- comment un lien youtube est lu de manière intégrée dans un tweet ? Il a pas l'air d'être lu, c'est du texte
- quel format vidéo le plus adapté pour Twitter (rapport qualtié/poids) ? du mp4 (on peut baisser la qualité en lecture.)

## Architecture

La base de données d’extraits doit être simple à éditer (ajouter, supprimer)

On a un fichier texte source **simple à éditer** qui **déclare** les extraits choisis. Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timestamps début et fin de l’extrait.

Le projet a l’architecture suivante :

- fichier source au format xml (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis
- un petit programme client web qui sert d'interface utilisateur pour alimenter le fichier source sans le manipuler directement. Il proposera notamment de preview l'extrait avant de l'ajouter
- un programme se charge de lire le fichier texte et scanner tous les extraits. S'il détecte un extrait retiré, il supprime l'extrait du dépôt, si nouvel extrait il s'occupe de l'ajouter au dépot. Une petite base de données est ensuite mis à jour et permet de maintenir le dépôt des extraits, d'ajouter de la logique supplémentaire (par exemple indiquer si tel extrait a déjà été posté pour éviter les doublons).

- un deuxième programme *post* à une fréquence donnée des extraits issus de cette base de données (le twitter bot)

## Hack commencer par là

Au plus simple, manipuler directement le fichier. On peut ensuite voir sur le serveur qui heberge les vidéos: 

- la liste complète des vidéos entières téléchargées à la main
- la liste des extraits (avec leur nom)

## Téléchargement de vidéos youtube depuis un server mutualisé

Cela pose problème

- je n'ai pas tous les droits sur le serveur pour installer ce que je veux (comme le wrapper youtube-download)
- youtube change regulièrement d'api donc c'est compliqué de développer un script pour télécharger une vidéo youtube sans qu'il soit difficile à maintenir
- les librairies existantes souffrent toutes du point précédent

Le plus simple serait de télécharger manuellement les vidéos d'ackboo (il en sort pas une par jour non plus j'imagine).

## Solution collaborative

- Une personne en charge de pousser les vidéos sources (vidéos completes) sur le serveur via ftp dans le dossier `sources`
- formatter le nom de la video comme `{nom-serie}-{numero-video|code}.mp4`, tout en [snake-case](https://fr.wikipedia.org/wiki/Snake_case).


## Formattage vidéo

- Vidéo en 720p en .mp4, 60fps
- Son à 128 voire 96 kbps
- cut a la miliseconde

## Téléchargement 

- télécharger 

## Ressources

- [Twitter API]()
- [Rate Limits de la Twitter API]()
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [Youtube clips features](https://www.youtube.com/watch?v=A63imEmP_-I)
- [YouTube Video Downloader Script in PHP](https://www.phpzag.com/php-youtube-video-downloader-script/)