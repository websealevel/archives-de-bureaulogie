# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*

## Projet

Créer un bot twitter qui post des courts extraits vidéos d’ackboo dans une grammaire *out of context*.

La base de données d’extraits doit être simple à éditer (ajouter, supprimer). On a un fichier texte maître **simple à éditer** qui **déclare** les extraits choisis. Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timestamps début et fin de l’extrait.

Le projet aura l’architecture suivante

- un programme se charge de lire le fichier texte et scanner tous les extraits. S'il détecte un extrait retiré, il supprime l'extrait du dépôt, si nouvel extrait il s'occupe de l'ajouter au dépot. Une petite base de données est ensuite mis à jour et permet de maintenir le dépôt des extraits, d'ajouter de la logique supplémentaire (par exemple indiquer si tel extrait a déjà été posté pour éviter les doublons).

- un deuxième programme post à une fréquence donnée des extraits issus de cette base de données (le twitter bot)



## Ressources

- [Twitter API]()
- [Rate Limits de la Twitter API]()
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)