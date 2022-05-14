# Specifications et *backlog* des archives de bureaulogie

- [Specifications et *backlog* des archives de bureaulogie](#specifications-et-backlog-des-archives-de-bureaulogie)
  - [Specs](#specs)
    - [Architecture générale](#architecture-générale)
      - [Les différents composants](#les-différents-composants)
      - [Le *fichier source*](#le-fichier-source)
    - [Cahier des charges pour l'encodage vidéo/audio des extraits](#cahier-des-charges-pour-lencodage-vidéoaudio-des-extraits)
    - [Formattage des noms](#formattage-des-noms)
      - [Fichier vidéo *source*](#fichier-vidéo-source)
      - [Fichier vidéo *extrait*](#fichier-vidéo-extrait)
      - [Timecode](#timecode)
    - [Gestion des fichiers `downloads`, `sources` et `extraits`](#gestion-des-fichiers-downloads-sources-et-extraits)
      - [Fichier `download`](#fichier-download)
      - [Fichier `source`](#fichier-source)
      - [Fichier `extrait`](#fichier-extrait)
    - [Twitter Bots](#twitter-bots)
    - [Modération](#modération)
    - [Pages](#pages)
    - [Rôles et droits associés](#rôles-et-droits-associés)
    - [Métrique des ressources (extraits et references biblios)](#métrique-des-ressources-extraits-et-references-biblios)
  - [Backlog](#backlog)
    - [Général](#général)
    - [Code source / Dépôt](#code-source--dépôt)
    - [Ecran - Importer une source/Lister les sources](#ecran---importer-une-sourcelister-les-sources)
    - [Ecran - Creer un extrait/Lister les extraits](#ecran---creer-un-extraitlister-les-extraits)
    - [Ecran Ajouter une ressource biblio](#ecran-ajouter-une-ressource-biblio)
    - [Ecran Modération des extraits vidéos](#ecran-modération-des-extraits-vidéos)
    - [Ecran Modération des ressources bibliographiques](#ecran-modération-des-ressources-bibliographiques)
  - [SEO](#seo)
  - [Sécurité](#sécurité)

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

Dire qu'on ne modifie pas quelque chose de soumis, on le valide ou non. On peut se permettre de corriger des fautes pour les ref biblio (en accord avec la méta) et si ça pose un pb de sens, dire que nous aussi (bienveillant) "on fait des fotes(barré) fautes". Pour les extraits on corrige que si ça cree un pb de compréhension.

### Pages

- Charte : strict sur le contenu, toute infraction a la charte, banissement (IP banni et compte suspendu)
- Qui sommes nous ? Humour, parodie, ref à acknoo et le tribunal des bureaux
- Open source : lien vers le projet,contribuer, forker, contribuer etc...
- Nous soutenir : lien vers patreon, expliquer pourquoi (hebergement, travail, nom de domaine, vidéos) avec des chiffres. Engagement à corriger les bugs et la sécurité mais soutien sans attendre une garantie en retour. Si le projet n'est plus maintenu les soutiens seront automatiquement cloturés.
- Rencontré un bug ? Mettre un lien vers les issues du dépot pour en déposer une
- Mettre un message pour découverte de faille de sécu, envoyer par mail avant d'ouvrir une issue
- Mettre un message pour les hacker "bravo, peux tu m'expliquer comment tu as fait ? ça m'interesse."
- Contact : le mail contact@archives-de-bureaulogie.fr
- Extraits
- Sources
- Références biblios
- Playlist Extraits (Twitter bot), admin only
- Playlist Références biblios (Twitter bot), admin only

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
  - get l'API pour twitter (interroger la playlist, nextItem extrait et nextItem reference)

### Métrique des ressources (extraits et references biblios)

- Metriques de popularité en recueillant les retweet et likes de chaque ressource.

## Backlog

Suivi des tâches à réaliser.

### Général

- *normaliser le volume* avec une valeur par défaut (voir ré-encodage) au post-montage. Le volume de la piste audio est défini par défaut par une métadonnée. Il faut que la normalisation se fasse sur le cut et non sur la vidéo entière (normalisation est une fonction de la piste entière, analyse les pics/creux de volume sur tout le volume et essaie de normaliser à partir de ça. Donc attention à ça) []
- activer/desactiver inscriptions au site [x]
- activer/desactiver mode maintenance du site [x]
- utliser youtube-dl en local [x]
- utiliser ffmpeg/ffprobe en local [x]
- pas de password recover [x]
- ecran [Ajouter une source/Lister sources](#ecran---importer-une-sourcelister-les-sources) [][x]
- ecran [Creer un extrait/Lister les extraits](#ecran---creer-un-extraitlister-les-extraits) []
- ecran Ajouter une entrée Biblio
- ecran Editer une entrée Biblio  
- ecran Liste biblios en attente de modération  
- ecran Liste des bureaulogues (tous les contributeurs avec leurs contributions) 
- ecran Modération des extraits
- ecran Modération des ressources biblios
- gestion des emails (activation du compte, suite à une modération, banissement du compte)

### Code source / Dépôt

- tout basculer dans des namespaces (PSR-4) []
- deplacer le fichier source+dtd dans un dossier a part[]
- utiliser phpcbs et phpcs pour formatter le code au standard PHP Pro PSR []
- generer la documentation (PSR-5 en cours) avec phpdocumentor []
- mettre en place un template pour déposer une issue (s'inspirer de celui de symfony)[]
  
### Ecran - Importer une source/Lister les sources

- `preg_match` sur le name du formulaire ne fonctionne pas, à fixer (check_download_request_form, ligne 102) [ ]
- télécharger une vidéo apres soumission du formulaire [x]
- progression téléchargement [x]
- téléchargement en arrière plan [x]
- charger prévisualisation quand l'url est collée (js) [x]
- lancer le téléchargement via une requete AJAX sur l'API de l'appli (non bloquant) [x]
- si le téléchargement échoue fails gracefully sans deconnecter [x]
- si le téléchargement va à son terme déclarer la nouvelle source dans le fichier source []
- empecher le téléchargement si une ressource avec la meme url est déjà déclarée dans le fichier source[]
- empecher un téléchargement d'une ressource à une url identique si téléchargement avec la meme url "en cours"(downloading)[]
- afficher les erreurs dans le formulaire (js) []
- fixer bug format (plus de son)[]
- nettoyer le cache de youtube dl après chaque dl []
- recuperer le PID du processus youtube-dl du download []
- proposer dans téléchargement en cours la possibilité d'annuler le dl (grace au pid) []

### Ecran - Creer un extrait/Lister les extraits

- lister tous les extraits asociés à la source sélectionnée []. 
- afficher deux elements vidéos : source et extrait []
- preview de la vidéo source + preview de l'extrait
- mettre en pause la vidéo source si play video extrait et vice versa
- indique le timecode a tout moment
- timecode entrée et de sortie éditables
- manipuler les timecodes via une interface graphique sur le player (luxe)
- plusieurs cut dans un seul fichier via des marqueurs avec label des extraits, en un clic exporter tous les marqueurs (luxe)


### Ecran Ajouter une ressource biblio

- si role > modérateur, afficher la liste des ressources présentes (html)
- formulaire avec select type de ressource (livre, article, podcast...)


### Ecran Modération des extraits vidéos

A venir...

### Ecran Modération des ressources bibliographiques

A venir...

## SEO

- ajouter un sitemap
- robots
- revoir les méta de header pour les mettre a jour SEO [x]
- adapter les métas pour chaque page []
  
## Sécurité

- role_has_cap a implementer !!! []
- déclarer une limite d'extraits / utilisateur / source (20 par exemple) []
- envoyer un email pour valider le compte []
- ajouter une colonne status du compte (actif, banni)
- regarder comment mieux sécuriser les sessions [x]
- ajouter honey pot dans le form d'inscription (voir ce que je peux faire d'autre pour éviter les spams) []
- limiter le nb de sources extraits / user []
- interdire de dl si espace disque donné atteint
- enlever la version d'apache dans la requete de reponse
- dev un mode maintenance simple (fichier .env si c'est `On` on charge le template `maintenance.php` dès l'`index.php` avant le router) [x]
- faire un test de pénétration []
- couvrir de tests les droits et capabilites []
- mettre en place des roles et capacités associes [x]
- creer un utilisateur twitter pour chaque twitter bot avec un role twitter_user []
- installer un paquet qui gere les bans d'IP (type wordfence), tentatives de login repetees, brut force []
- pour les comptes admin associer une liste blanche d'adresses IP en plus de l'authentification []
- refactor formulaires avec champs requis, label cliquables et obligation d'etre majeure et d'accepter la charte [x]
- telechargement de sources autorisé que depuis la chaine de canardPC []
- finir la confirmation d'authentification sur les actions sensibles []
- installer un moniteur éthique pour analyser la fréquentation du site[]
- vérifier que les error/exception handlers sont bien intégrés à chaque script [x]
- certaines exceptions ne devraient pas logout l'utilisateur (ex téléchargement échoué), mauvais pour l'ux [x] => solution: Les try/catch localement, sinon par défaut ça deconnecte
- backup : dump de la base, extraits.xml et extraits.dtd suffisent
- recover: regenerer tous les fichiers à partir du fichier source grâce a une fonction core []
- desactiver/activer acces aux comptes Twitter []
- desactiver facilement les comptes Twitter []
- desactiver facilement le site []


