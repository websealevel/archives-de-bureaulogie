# Specifications et *backlog*

Les spécifications techniques du projet et le backlog.

## Specs

### Architecture générale

#### Les différents composants

Le projet a l’architecture suivante :

- *fichier source* au format XML (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis. Il est *la base de données des extraits*.
- une application web qui sert d'interface utilisateur pour éditer les extraits (ajouter, modifier, supprimer) et soumettre des références bibliographiques
- une application *core* qui se charge de lire/manipuler le fichier source pour créer/supprimer/modifier les extraits
- des applis qui *post* à une fréquence donnée des extraits issus de cette base de données (aka les Twitter Bot)
- une base de données pour gérer les comptes utilisateurs et les rôles associés

#### Le *fichier source*

La base de données des extraits est gérée par le `fichier source`. Le fichier source est `extraits.xml`. Il contient tout le travail éditorial de déclaration des extraits. Ce fichier est manipulé par différents programmes (ou à la main mais prudence !) pour gérer les extraits (création, modification, suppression).

Ce fichier est **simple à éditer** et il **déclare** les extraits choisis. Il fait office de *source de vérité* et il définit l'état de la base de données d'extraits (quels extraits sont présents ou non). Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timecodes début et fin de l’extrait (c) d'un slug (d) d'une description.

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
- piste audio normalisée sur l'intégralité de l'extrait (et non de la source) []

Ces paramètres s'appliquent au téléchargement des vidéos sources et à l'encodage des extraits générés par l'application.

### Formattage des noms

#### Fichier vidéo *source*

Voir [ici](sources/README.md)

#### Fichier vidéo *extrait*

Voir [ici](extraits/README.md)

#### Timecode

Les timecodes (instant de début ou de fin de l'extrait) doivent être formattés au format `hh.mm.ss.lll` avec 

- `h` l'heure
- `m` la minute
- `s` la seconde
- `l` la miliseconde

Ils doivent être compris entre `00.00.00.000` et la durée totale de la vidéo.

### Gestion des fichiers `downloads`, `sources` et `extraits`

#### Fichier `download`

Quand une nouvelle vidéo est téléchargée via l'application elle n'est pas automatiquement ajoutée aux `sources`. Elle est enregistrée dans le dossier `downloads`. Elle doit être explicitement approuvée par un administrateur pour devenir une *source*.

#### Fichier `source`

Les fichiers *sources* sont les vidéos téléchargées depuis youtube entières et servent de source aux extraits. Elles se trouvent dans le dossier `sources`.

Les fichiers *sources* **doivent respecter [un format](sources/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

#### Fichier `extrait`

Les *extraits* sont les extraits vidéos des [sources](#fichiers-sources). Ils sont générés automatiquement à partir des informations fournies dans le [fichier source](#le-fichier-source). Ils se trouvent dans le dossier `extraits`.

Les fichiers *extraits* **doivent respecter [un format](extraits/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

Un extrait doit faire **au moins 1 seconde**, sinon il ne sera pas généré et une exception sera levée.

### Twitter Bots

Page de config:

- frequence de tweets, heure etc...
- créer une playlist éditable pour mettre l'ordre des extraits
- dans la playlist on voit des métas (nb de fois extrait tweetés)
- voir les tweets plus méta (likes, retweeks)

### Modération

Modération des sources biblios proposées: accepter ou refuser. 

Envoyer un mail 
- "Féliciations, notre archiviste a validé votre ressource bibliographique. Elle a été intégrée au corpus existant."
- "Nous avons le regret de vous annoncer que votre ressource bibliohraphique a été rejeté pour la raison suivante {X}. Cette décision est malheureusement irrévocable."

### Rôles et droits associés

Chaque rôle de l'étage supérieur hérite des capacités des rôles de l'étage inférieur.

- superadmin
  - ajouter un admin
  - changer le role de admin à modérateur
- admin 
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

A part

- twitterbot
  - get l'API twitter

### Métrique des ressources (extraits et references biblios)

- Metriques de popularité en recueillant les retweet et likes de chaque ressource.

## Backlog

### taches

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

### Interface graphique de *cut*

- preview de la vidéo source + preview de l'extrait
- mettre en pause la vidéo source si play video extrait et vice versa
- indique le timecode a tout moment
- timecode entrée et de sortie éditables
- manipuler les timecodes via une interface graphique sur le player (luxe)
- plusieurs cut dans un seul fichier via des marqueurs avec label des extraits, en un clic exporter tous les marqueurs (luxe)
- *normaliser le volume* avec une valeur par défaut (voir ré-encodage) au post-montage. Le volume de la piste audio est défini par défaut par une métadonnée. Il faut que la normalisation se fasse sur le cut et non sur la vidéo entière (normalisation est une fonction de la piste entière, analyse les pics/creux de volume sur tout le volume et essaie de normaliser à partir de ça. Donc attention à ça)

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
- creer un utilisateur twitter pour chaque twitter bot avec un role twitter_user
- utiliser pcbs et l'autre pour formatter le code au standard PSR
- installer un paquet qui gere les bans d'IP (type wordfence), tentatives de login repetees, brut force
- ajouter honey pot dans le form d'inscription (voir ce que je peux faire d'autre)
- pour les comptes admin on va ajouter des adresses IP en plus de l'authentification


