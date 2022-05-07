# Backlog

- normaliser l'audio SUR l'extrait []
- activer/desactiver inscriptions au site [x]
- utliser youtube-dl en local
- utiliser ffmpeg en local
- pas de password recover [x]
- ecran Creer un extrait []
- ecran Télécharger une source 
- ecran Ajouter une entrée Biblio
- ecran Editer une entrée Biblio  
- ecran Liste biblios en attente de modération  
- ecran Liste des extraits par Source 
- ecran liste des bureaulogues  
- ecran nos contributeurs
- modération

## Pour le twitter Bot

Page de config:

- frequence de tweets, heure etc...
- créer une playlist éditable pour mettre l'ordre des extraits
- dans la playlist on voit des métas (nb de fois extrait tweetés)
- voir les tweets plus méta (likes, retweeks)

## Modération

Modération des sources biblios proposées: accepter ou refuser. 

Envoyer un mail 
- "Féliciations, notre archiviste a validé votre ressource bibliographique. Elle a été intégrée au corpus existant."
- "Nous avons le regret de vous annoncer que votre ressource bibliohraphique a été rejeté pour la raison suivante {X}. Cette décision est malheureusement irrévocable."

## Sécurité

- regarder comment mieux sécuriser les sessions [x]
- limiter le nb de sources téléchargéés/extraits / user
- interdire de dl si espace disque donné atteint
- enlever la version d'apache dans la requete de reponse
- dev un mode maintenance simple (fichier .env si c'est On on charge le template maintenance.php dès l'index avant le router. Et voilà) [x]
- revoir les méta de header pour les mettre a jour SEO [ ]
- faire un test de pénétration
- couvrir de tests les droits et capabilites []
- mettre en place des roles et capacités associes [x]


### Rôles et droits associés

Chaque rôle de l'étage supérieur hérite des capacités des rôles de l'étage inférieur.

- superadmin
  - tous les droits admin
  - ajouter un admin
  - changer le role de admin à modérateur
- admin 
  - tous les droits modérateur
  - ajouter un modérateur
  - éditer ressources bilbio de tout le monde
  - lister toutes les ressources biblio
  - lister tous les extraits
  - ajouter une source
  - supprimer une source
  - bannir le compte modérateur/contributeur
  - changer le role de modérateur vers contributeur
- modérateur
  - modérer une ressource biblio
  - modérer un extrait vidéo
- contributeur
  - proposer un extrait vidéo
  - proposer une ressource biblio
  - voir ses extraits vidéos par source
  - voir ses ressources biblios

## Ressources biblios

- Metriques de popularité en recueillant les retweet et likes de chaque ressource.