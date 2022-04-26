# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*



## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md).

## Projet

Créer un bot twitter qui *post* des courts extraits vidéos d’ackboo dans une grammaire *out of context*.

## Architecture

Plusieurs solutions

### Questions

- comment une vidéo démarre auto dans un tweet ?
- comment un lien youtube est lu de manière intégrée dans un tweet ?

## Officielle : Youtube API (et clips?)

Youtube propose de faire des clips (extraits vidéos). Cree un lien vers la vidéo originale (cree pas du contenu) et permet de lire l'extrait ou l'intégralité.

Les limites

- 5s minimum
- 60s max
- le créateur peut empecher la possibilité de faire des extraits sur ses vidéos
- pas l'air d'avoir d'API pour les clips
- pas possible d'organiser les clips en playlist (pour les lire tous d'affilée par exemple ou les organiser si besoin)

Les bonus

- les vues, analytics et monetisation vont bénéficier au créateur (comme si vidéo originale)
- les clips sont sauvés sous l'onglet `Vos extraits`. Donc on peut créer une playlist de clips sur notre compte, c'est cool. Par contre le lien devient invalide si on supprime l'extrait

## Hack 

La base de données d’extraits doit être simple à éditer (ajouter, supprimer)

On a un fichier texte source **simple à éditer** qui **déclare** les extraits choisis. Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timestamps début et fin de l’extrait.

Le projet a l’architecture suivante

- fichier source au format xml (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis
- un petit programme client web qui sert d'interface utilisateur pour alimenter le fichier source sans le manipuler directement. Il proposera notamment de preview l'extrait avant de l'ajouter
- un programme se charge de lire le fichier texte et scanner tous les extraits. S'il détecte un extrait retiré, il supprime l'extrait du dépôt, si nouvel extrait il s'occupe de l'ajouter au dépot. Une petite base de données est ensuite mis à jour et permet de maintenir le dépôt des extraits, d'ajouter de la logique supplémentaire (par exemple indiquer si tel extrait a déjà été posté pour éviter les doublons).

- un deuxième programme *post* à une fréquence donnée des extraits issus de cette base de données (le twitter bot)


## Ressources

- [Twitter API]()
- [Rate Limits de la Twitter API]()
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [Youtube clips features](https://www.youtube.com/watch?v=A63imEmP_-I)