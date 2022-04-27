# Extraits

Contient les extraits générés automatiquement à partir des [sources](../sources/README.md).

## Format du nom

Les extraits sont générés automatiquement au format 

~~~
{nom-serie}--{numero|identifiant de la video}--{slug}--{code temps début}--{code-temps-fin}.mp4
~~~

où `slug` est un élément XML de l'élément extrait. 

## Slug

Un [slug](https://fr.wikipedia.org/wiki/Slug_(journalisme)) est une chaine de caractères au format contraint, c'est à dire qu'il

- ne contient pas d'espaces, ils sont remplacés par des hyphens (-)
- tous les caractères sont en minuscules
- ne contient que des caractères alphanumériques (lettres standards ASCII et chiffres)

**Toute vidéo ne respectant pas ce formattage sera supprimée** soit par un administrateur soit par un programme de nettoyage.