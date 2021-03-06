# Specifications et *backlog* des archives de bureaulogie

- [Specifications et *backlog* des archives de bureaulogie](#specifications-et-backlog-des-archives-de-bureaulogie)
  - [Specs](#specs)
    - [Architecture générale](#architecture-générale)
      - [Les différents composants](#les-différents-composants)
      - [Le *fichier source* (module core)](#le-fichier-source-module-core)
      - [Module source](#module-source)
    - [Cahier des charges pour l'encodage vidéo/audio des extraits](#cahier-des-charges-pour-lencodage-vidéoaudio-des-extraits)
    - [Formattage des noms](#formattage-des-noms)
      - [Fichier vidéo *source*](#fichier-vidéo-source)
      - [Fichier vidéo *extrait*](#fichier-vidéo-extrait)
      - [Timecode](#timecode)
    - [Gestion des fichiers `sources` et `extraits`](#gestion-des-fichiers-sources-et-extraits)
      - [Fichier `source`](#fichier-source)
      - [Fichier `extrait`](#fichier-extrait)
    - [Unicité d'une source](#unicité-dune-source)
    - [Unicité d'un clip](#unicité-dun-clip)
    - [Twitter Bots](#twitter-bots)
    - [Modération](#modération)
    - [Pages](#pages)
    - [Rôles et droits associés](#rôles-et-droits-associés)
    - [Métrique des ressources (extraits et references biblios)](#métrique-des-ressources-extraits-et-references-biblios)
  - [Backlog](#backlog)
    - [architecture](#architecture)
    - [Général](#général)
    - [Core](#core)
    - [Ecran - Importer une source/Lister les sources](#ecran---importer-une-sourcelister-les-sources)
    - [Ecran - Creer un extrait/Lister les extraits](#ecran---creer-un-extraitlister-les-extraits)
    - [Ecran Ajouter une ressource biblio](#ecran-ajouter-une-ressource-biblio)
    - [Ecran Modération des extraits vidéos](#ecran-modération-des-extraits-vidéos)
    - [Ecran Modération des ressources bibliographiques](#ecran-modération-des-ressources-bibliographiques)
    - [SEO](#seo)
    - [Core (CLI/CGI)](#core-clicgi)
  - [Sécurité](#sécurité)
  - [Performances](#performances)
  - [Gestion des sessions](#gestion-des-sessions)
  - [Features additionnelles](#features-additionnelles)
  - [Comptes twitter](#comptes-twitter)
  - [Référence biblio](#référence-biblio)
    - [Livre](#livre)
    - [Site web](#site-web)
    - [Podcasts](#podcasts)
    - [Film](#film)
    - [Série](#série)
    - [Artiste](#artiste)
    - [Chaine youtube](#chaine-youtube)
    - [Article scientifiques](#article-scientifiques)
  - [Magazine/Journal](#magazinejournal)
    - [Auteure (a asocier aux livres)](#auteure-a-asocier-aux-livres)
    - [Editeurs (a associer aux libres)](#editeurs-a-associer-aux-libres)
  - [Critique](#critique)
  - [Backup](#backup)
  - [Roadmap](#roadmap)
    - [v1.0](#v10)
    - [v2.0](#v20)
    - [v3.0](#v30)
    - [v4.0](#v40)

## Specs

### Architecture générale

#### Les différents composants

Le projet a l’architecture suivante :

- *fichier source* au format XML (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis. Il est *la base de données des extraits*. Il est compris dans un module (sous-module du module *core*) qui encapsule l'acces aux fichiers (écriture/lecture), la gestion des métadonnées sur les vidéos sources et les extraits.
- une application web qui sert d'interface utilisateur pour éditer les extraits (ajouter, modifier, supprimer) et soumettre des références bibliographiques
- une application *core* qui se charge de lire/manipuler le fichier source pour créer/supprimer/modifier les extraits
- des applis qui *post* à une fréquence donnée des extraits issus de cette base de données (aka les Twitter Bot)
- une base de données pour gérer les comptes utilisateurs et les rôles associés

#### Le *fichier source* (module core)

La base de données des extraits est gérée par le `fichier source`. Le fichier source est `extraits.xml`. Il contient tout le travail éditorial de déclaration des extraits. Ce fichier est manipulé par différents programmes (ou à la main mais prudence !) pour gérer les extraits (création, modification, suppression).

Ce fichier est **simple à éditer** et il **déclare** les extraits choisis. Il fait office de *source de vérité* et il définit l'état de la base de données d'extraits (quels extraits sont présents ou non). Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timecodes début et fin de l’extrait (c) d'un slug (d) d'une description.

#### Module source

**GROS REFACTOR A FAIRE** : la couche fichier source devrait etre un module qui masque complètement à l'application core et à l'application web la gestion de fichiers. Ce module doit offrir une interface aux applis core et web pour checker si une source existe déjà, importer une source, ajouter un extrait, checker qu'un etrait existe déjà, etc.

### Cahier des charges pour l'encodage vidéo/audio des extraits

Chaque extrait sera embarqué dans un tweet. Il faut donc veiller à obtenir un bon format et un bon rapport qualité/poids (son et vidéo). Quelques Mo pour un extrait de 2min par exemple.

Après une phase de tests on retiendra les paramètres d'encodage avec les valeurs suivantes

- **piste vidéo**
  -  résolution max : 720p [x]
  -  format: mp4 [x]
  -  frame rate max : 30fps [x]
  -  video bitrate : 369 kbps [x]
  -  durée comprise entre 1s et 2m20s [x] (contrainte Twitter)
  -  taille inférieure à 512M [x] (contrainte Twitter)
- **piste audio**
  - data-transfer rate : 96 kbps [x]
  - audio bitrate/échantillonage : 48000 Hz(standard dans un fichier vidéo)  [x]
- cut à la milliseconde [x]
- taille maximale ([imposée par Twitter](https://help.twitter.com/en/using-twitter/twitter-videos)) : 2min20s [x]
- piste audio normalisée (volume max mis à 0dB) sur chaque extrait (et non sur la vidéo source complète) [x]

Ces paramètres s'appliquent *à la fois* au téléchargement des vidéos sources et à l'encodage des extraits générés par l'application.

### Formattage des noms

#### Fichier vidéo *source*

Voir [ici](./DocumentRoot/sources/README.md)

#### Fichier vidéo *extrait*

Voir [ici](./DocumentRoot/extraits/README.md)

#### Timecode

Les timecodes (instant de début ou de fin de l'extrait) doivent être formattés au format `hh:mm:ss.lll` avec 

- `h` l'heure
- `m` la minute
- `s` la seconde
- `l` la miliseconde

Ils doivent être compris entre `00:00:00.000` (inclus) et la durée totale de la vidéo.

### Gestion des fichiers `sources` et `extraits`

#### Fichier `source`

Les fichiers *sources* sont des vidéos téléchargées depuis youtube et servent de source aux extraits. Elles se trouvent dans le dossier `sources`.

Les fichiers *sources* **doivent respecter [un format](./DocumentRoot/sources/README.md)** sinon elles finiront pas être **supprimées automatiquement**.

#### Fichier `extrait`

Les *extraits* sont les extraits vidéos issus des [sources](#fichiers-sources). Ils sont générés automatiquement **à partir** des informations fournies dans le [fichier source](#le-fichier-source). Ils se trouvent dans le dossier `extraits`.

Les fichiers *extraits* **doivent respecter [un format][un format](./DocumentRoot/extraits/README.md)** sinon ils finiront pas être **supprimées automatiquement**.

Un extrait doit faire **au moins 1 seconde**, sinon il ne sera pas généré et une exception sera levée et moins de **2min20s**, pour être publiable sur Twitter.

### Unicité d'une source

- url

### Unicité d'un clip

- nom source + timecodes identiques. Si sur une meme source, deux clips ont les memes timecodes, ce sont les mêmes extraits.  

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
- Qui sommes nous ? Humour, parodie, ref à ackboo et au tribunal des bureaux
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

Suivi des tâches

### architecture

- migrer de php-cgi/apache à php-fpm/nginx [x]

### Général

- *normaliser le volume* avec une valeur par défaut (voir ré-encodage) au post-montage. Le volume de la piste audio est défini par défaut par une métadonnée. Il faut que la normalisation se fasse sur le cut et non sur la vidéo entière (normalisation est une fonction de la piste entière, analyse les pics/creux de volume sur tout le volume et essaie de normaliser à partir de ça. Donc attention à ça) []
- activer/desactiver inscriptions au site [x]
- activer/desactiver mode maintenance du site [x]
- utliser youtube-dl en local [x]
- utiliser ffmpeg/ffprobe en local [x]
- pas de password recover [x]
- ecran [Ajouter une source/Lister sources](#ecran---importer-une-sourcelister-les-sources) [x][x]
- ecran [Creer un extrait/Lister les extraits](#ecran---creer-un-extraitlister-les-extraits) [x][x]
- ecran Ajouter une entrée Biblio
- ecran Editer une entrée Biblio  
- ecran Liste biblios en attente de modération  
- ecran Liste des bureaulogues (tous les contributeurs avec leurs contributions) 
- ecran Modération des extraits
- ecran Modération des ressources biblios
- gestion des emails (activation du compte, suite à une modération, banissement du compte)

### Core

- ne pas enregistrer d'array dans `$_ENV` pour les variables d'environnement, issue déposée [ici](https://github.com/symfony/symfony/issues/46348) [x]
- tout basculer dans des namespaces (PSR-4) []
- utiliser phpcbs et phpcs pour formatter le code au standard PHP Pro PSR []
- generer la documentation (PSR-5 en cours) avec phpdocumentor []
- mettre en place un template pour déposer une issue (s'inspirer de celui de symfony)[]
- deplacer le fichier source+dtd dans un dossier a part[]

### Ecran - Importer une source/Lister les sources

- `preg_match` sur le name du formulaire ne fonctionne pas, à fixer (`check_download_request_form`, ligne 102) [ ]
- checker le nonce (token) du formulaire de soumission de vidéo source []
- télécharger une vidéo apres soumission du formulaire [x]
- progression téléchargement [x]
- téléchargement en arrière plan [x]
- charger prévisualisation quand l'url est collée (js) [x]
- lancer le téléchargement via une requete AJAX sur l'API de l'appli (non bloquant) [x]
- si le téléchargement échoue fails gracefully sans deconnecter [x]
- si le téléchargement va à son terme déclarer la nouvelle source dans le fichier source [x]
- empecher le téléchargement si une ressource avec la meme url est déjà déclarée dans le fichier source[x]
- empecher un téléchargement d'une ressource avec une url identique à l'urld'un téléchargement en cours (downloading)[x]
- afficher les erreurs dans le formulaire (js) [x]
- basculer dl en cours vers historique, clean interface (js) []
- fixer bug format (plus de son)[x]
- nettoyer le cache de youtube dl après chaque dl [x]
- recuperer le PID du processus youtube-dl du download []
- proposer dans téléchargement en cours la possibilité d'annuler le dl (grace au pid) []

### Ecran - Creer un extrait/Lister les extraits

- charger la video source en fonction du select[x]
- charger la video extrait en fonction des timecodes[x]
- en jquery manipuler la preview clip pour charger juste la vidéo sur la durée du clip [x]
- afficher deux elements vidéos : source et extrait [x]
- preview de la vidéo source + preview de l'extrait [x]
- mettre en pause la vidéo source si play video extrait et vice versa [x]
- indique le timecode a tout moment [x]
- timecode entrée et de sortie éditables [x]
- manipuler les timecodes via une interface graphique (sans les rentrer à la main) [x]
- pouvoir prévisualiser un temps apres le timecode de fin [x]
- renseigner titre, description [x]
- options d'édition [x]
- valider le formulaire [x]
- valider token, authentification, cap [x]
- valider timecodes [x]
- valider que la source existe et qu'elle est déclarée[x]
- déclarer l'extrait s'il n'existe pas sur la source [x]
- générer l'extrait [x]
- lister tous les extraits asociés à la source sélectionnée [x]
- controles custom d'édition vidéos [x]
- déclarer des marqueurs sur les vidéos sources pour repérer des extraits à faire plus tard []


### Ecran Ajouter une ressource biblio

- si role > modérateur, afficher la liste des ressources présentes (html) [ ]
- formulaire avec select type de ressource (livre, article, podcast...) [ ]
- trimer et lower le nom de la ref et en faire une analyse sémantique pour trouver des suggestion proches en plus d'un match parfait (check d'unicité) [ ]
- mettre en place une liste noire de mots [ ]

### Ecran Modération des extraits vidéos

A venir...

### Ecran Modération des ressources bibliographiques

A venir...

### SEO

- ajouter un sitemap
- robots
- revoir les méta de header pour les mettre a jour SEO [x]
- adapter les métas pour chaque page []
  
### Core (CLI/CGI)

Partie core de l'application, indépendante de l'appli web

- action_update_clips[ ]
  - generate_clips[x]
  - clean_clips[]
    - clean invalid clips[] (format nom invalide, timecodes invalides, extrait orphelin cad que la source n'est pas déclarée dans le fichier source)
    - clean undeclared clips[ ]
- action_clean_sources[ ]
  - clean invalid sources [ ]
  - clean undeclared sources []
- generate rapports []
  - dans un fichier de log si en mode cgi [x]
  - sur la sortie standard en mode cli [x]
- backup : dump de la base, extraits.xml et extraits.dtd suffisent [ ]
- recover: regenerer tous les fichiers à partir du fichier source grâce a une fonction core []


## Sécurité

- mettre des nonces (token) dans le formulaire de dl source [x]
- mettre des nonces (token) dans le formulaire de clip [x]
- `role_has_cap` a implementer !!! []
- déclarer une limite d'extraits / utilisateur / source (20 par exemple) []
- envoyer un email pour valider le compte []
- ajouter une colonne status du compte (actif, banni)
- regarder comment mieux sécuriser les sessions [x]
- ajouter honey pot dans le form d'inscription (voir ce que je peux faire d'autre pour éviter les spams) []
- limiter le nb de sources extraits / user []
- interdire de dl si espace disque donné atteint []
- enlever la version d'apache dans la requete de reponse [x]
- dev un mode maintenance simple (fichier .env si c'est `On` on charge le template `maintenance.php` dès l'`index.php` avant le router) [x]
- couvrir de tests les droits et `capabilites` []
- mettre en place des roles et capacités associées [x]
- creer un utilisateur twitter pour chaque twitter bot avec un role twitter_user []
- installer un paquet qui gere les bans d'IP (type wordfence), tentatives de login repetees, brut force []
- pour les comptes admin associer une liste blanche d'adresses IP en plus de l'authentification []
- refactor formulaires avec champs requis, label cliquables et obligation d'etre majeure et d'accepter la charte [x]
- telechargement de sources autorisé que depuis la chaine de canardPC []
- finir la confirmation d'authentification sur les actions sensibles []
- installer un moniteur éthique pour analyser la fréquentation du site[]
- vérifier que les error/exception handlers sont bien intégrés à chaque script [x]
- certaines exceptions ne devraient pas logout l'utilisateur (ex téléchargement échoué), mauvais pour l'ux [x] => solution: Les try/catch localement, sinon par défaut ça deconnecte []
- desactiver/activer acces aux comptes Twitter []
- desactiver facilement les comptes Twitter []
- desactiver facilement le site []
- donner les droits sur les dossier sources, extraits et sur les fichiers extraits.xml et extraits.xtd à l'user d'apache www-data (seul lui et le root peuvent les ouvrir et les lire) []
- stocker IP quand form soumis pour eventullement la bannir [ ]
- deplacer les comptes bannis dans une autre table [ ]
- faire un test de pénétration []



## Performances

- remplacer mp4 par webm
- utiliser directement la source url youtube au lieu de l'url de la video sur le serveur []
- creer une vidéo temporaire webm de moindre qualité a servir pour l'édition d'extrait (ce sera plus performeant) (mais garder la vidéo originale de bonne qualité)


## Gestion des sessions

- utiliser memcached ou redis pour optimiser gestion des sessions

## Features additionnelles

- contributeurs peuvent upvote/downvote les extraits
- creer un système de tags communautaire (modéré). Les contributeurs peuvent suggérer des tags s'ils n'existent pas déjà. Les modos peuvent les valider ou non. Ils rentrent alors dans les tags permis
- contributeurs peuvent chercher par épisode, par titre, par tag. Tester les idées de l'architecture de l'information

## Comptes twitter

- compte [Out of Context ackboo](https://twitter.com/archivesdb_fr) pour les archives vidéos
- compte pour les références biblios (a venir)


## Référence biblio 

- id*
- image
- type : livre, siteweb,podcast,film,serie,chaine YT,article,magazine,journal
  
### Livre

- titre*
- editeur
- ISBN
- auteur(e)s* (1-N)
- année de publication
- nombre de pages
- 4eme de couverture (résumé)
- critiques (1-N)

### Site web

- nom du site

### Podcasts

- nom série*
- nom radio
- nom animateur

### Film

- nom du film*
- realisateur
- année de production*
- producteur

### Série

- nom de la série*
- réalisateur
- nb de saisons 
- année de production*
- nb d'épisodes

### Artiste

- nom*
- domaine (groupe, musicien solo, danceur, architecte, etc.)*

### Chaine youtube

- nom*

### Article scientifiques

- nom journal
- numero
- nom article
- auteurs 1-N
- année

## Magazine/Journal

- nom
- année de création

### Auteure (a asocier aux livres)

- prenom
- nom auteur

### Editeurs (a associer aux libres)

- nom maison d'édition

## Critique

- auteur (fk auteur)

## Backup

Un backup du projet web demande donc
- `extraits.xml`
- `extraits.dtd`
- `dump de la base` (comptes)

Un backup du projet core demande seulement
- `extraits.xml`
- `extraits.dtd`


## Roadmap

### v1.0

- se créer un compte et se connecter [x]
- soumettre une vidéo source [x]
- télécharger la vidéo source [x]
- éditeur d'extrait custom [x]
- soumettre des extraits à partir des vidéos sources [x]
- produire des extraits [respectant des contraintes audio et vidéo] [x](#cahier-des-charges-pour-lencodage-vidéoaudio-des-extraits) (dimensions, encodages, normalization audio, etc.)
- lister les extraits sur chaque vidéo source [x]
- télécharger les extraits [x]
- marquer videos sources pour plus tard, ajouter/supprimer/recuperer (persisté en base) [x]

### v2.0

- intégration 
- ameliorer navigation avec markeurs de clip (markeur suivant, markeur précédent)
- mise en place des rôles
- soumettre une référence
- lister les références
- télécharger une référence (format?)
- modérer une référence si non proposée par un admin
- [code] : refactor en respectant le PSR-4
- implémenter la CLI pour que les admins puissent nettoyer les archives (via une UI)

### v3.0

- mise en place tes twitter bot
- mise en place de la playlist
- ecran admin twitter bots (réservé aux admins)
- SEO

### v4.0

- développer la plateforme communautaire
- creation de compte avec validation par email (pour éviter les spam)
- implémenter toutes les specs de sécurité, [voir les points mentionnés ci-dessus](#sécurité)
- mise en place de la modération

