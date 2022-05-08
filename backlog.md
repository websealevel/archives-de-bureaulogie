# Sepcs et backlog

## Specs

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

#### Pourquoi ?

Le développement d'une petite application web en surcouche de l'édition du fichier source parait rapidement indispensable pour plusieurs raisons:

- éviter de mauvaises manipulations sur le fichier source
- faciliter l'ajout d'extrait sans erreur
- prévisualiser l'extrait avant de l'enregistrer (un bon cut se fait à la miliseconde)
- pouvoir visionner la vidéo sur la page où on ajuste les timecodes pour éditer son cut de manière plus agréable

#### Besoins identifiés

- preview de la vidéo source
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


