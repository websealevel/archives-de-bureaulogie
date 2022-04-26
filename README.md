# out-of-context-ackboo-twitter-bot

## Projet

Créer un bot twitter qui post des courts extraits vidéos d’ackboo dans une grammaire *out of context*. 

La base de données d’extraits doit être simple à éditer (ajouter, supprimer). On a un simple fichier texte qui sera simple à éditer qui déclarera les extraits à sélectioner/retirer. Pour les extraits il faut maintenir une liste d’urls avec pour chaque url une liste de *timestamps* début et fin de l’extrait.

- un programme se chargera de lire le fichier texte et scannera tous les extraits. S'il détecte un extrait retiré, il supprime l'extrait du dépôt, si nouvel extrait il s'occupe de l'ajouter au dépot. Une petite base de données permettra de maintenir le dépôt des extraits et d'ajouter de la logique supplémentaire (par exemple indiquer si tel extrait a déjà été posté pour éviter les doublons).

- un deuxième programme postera à une fréquence donnée des extraits issus de cette base de données.

