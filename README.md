# out-of-context-ackboo-twitter-bot

*Un twitter bot qui post des extraits vidéos d'acknoo issus de sa chaîne le tribunal des bureaux*

<!-- ## Comment contribuer au dépôt ?

Envie de contribuer au dépôt en proposant un extrait ? [Lisez d'abord ceci](CONTRIBUTING.md). -->

## Projet

Créer un bot twitter qui *post* des courts extraits vidéos d’ackboo dans une grammaire *out of context*.

### Questions préalables

- comment une vidéo démarre auto dans un tweet ? Il faut embarquer le fichier directement, les liens embed ça marche pas. Donc télécharger la vidéo et l'uploader dans un tweet
- comment un lien youtube est lu de manière intégrée dans un tweet ? Il a pas l'air d'être lu, c'est du texte
- quel format vidéo le plus adapté pour Twitter (rapport qualtié/poids) ? du mp4 au format 720p et 60fps, son à 128kbps voire 96kbps

## Téléchargement de vidéos youtube depuis un server mutualisé

Cela pose problème

- je n'ai pas tous les droits sur le serveur pour installer ce que je veux (comme le wrapper youtube-download)
- youtube change regulièrement d'api donc c'est compliqué de développer un script pour télécharger une vidéo youtube sans qu'il soit difficile à maintenir
- les librairies existantes souffrent toutes du point précédent

Le plus simple serait de télécharger manuellement les vidéos d'ackboo et de les uploader sur le serveur.

## Encodage vidéo/audio des extraits

Chaque extrait sera embarqué dans un tweet. Il faut donc veiller à obtenir un bon format et un bon rapport qualité/poids (son et vidéo)

Après une phase de tests on retiendra les valeurs suivantes

- vidéo en 720p en .mp4, 30fps
- son à 128 voire 96 kbps
- précision : cut a la miliseconde nécessaire

## Conception

On a un fichier source **simple à éditer** qui **déclare** les extraits choisis. Il fait office de source de vérité et il définit l'état de la base de données d'extraits (quels extraits sont présents ou non). Pour chaque extrait, on a besoin (a) de l’url de la vidéo (b) d’un couple de timecodes début et fin de l’extrait (c) d'un slug (d) d'une description.

Le projet a l’architecture suivante :

- *fichier source* au format XML (simple à lire par les humains et les machines, permet de valider un schéma de données). Le fichier déclare l'intégralité des extraits choisis. Il est *la base de données des extraits*.
- un programme client web qui sert d'interface utilisateur pour éditer les extraits (ajouter, modifier, supprimer)
- un programme qui se charge de lire/manipuler le fichier source pour créer/supprimer/modifier les extraits
- un deuxième programme qui *post* à une fréquence donnée des extraits issus de cette base de données (aka le Twitter Bot)

## Utilisation

### 1. Télécharger une vidéo *source* depuis youtube

Télécharger la vidéo source désirée d'ackboo depuis youtube avec [jdownloader](https://jdownloader.org/).

### 2. Renommer la vidéo source

Voir [ici les instructions](sources/README.md) sur le format du nom de la vidéo source.

### 3. Uploader une nouvelle vidéo *source* sur le serveur

Pousser les vidéos sources (vidéos originales et complètes téléchargées depuis youtube) sur le serveur via le protocole FTP dans le dossier `sources`, en utilisant [Filezilla](https://filezilla-project.org/).

### 4. Ajouter ou supprimer un extrait (version manuelle)

#### Le *fichier source*

La base de données des extraits est gérée par le `fichier source`. Le fichier source est `extraits.xml`. Il contient tout le travail éditorial de déclaration des extraits. Ce fichier est manipulé par différents programmes (ou à la main mais prudence !) pour gérer les extraits (création, suppression).

#### Ajouter un extrait

Ouvrir `extraits.xml`.

Ajouter un élément `extrait` (copier-coller un extrait existant pour gagner du temps mais n'oubliez pas de l'éditer entièrement) **dans l'élément `source` parent dont sera tiré l'extrait**.

Renseigner dans chaque balise correspondante

- `slug` : le [slug](extraits/README.md#slug)
- `description` : une description courte de l'extrait (ie un titre)
- `debut` : le timecode du début. Voir [ici](#timecode) pour le format du timecode
- `fin` : le timecode de fin. Voir [ici](#timecode) pour le format du timecode

L'attribut `utilise` compte le nombre de fois où l'extrait a été twitté par le bot. L'initialiser à `0` par défaut.

L'extrait **doit faire au moins une seconde**.

Par exemple

~~~xml
<source name="le-tribunal-des-bureaux-1.mp4">
</source>
<source name="le-tribunal-des-bureaux-2.mp4">
        <extrait utilise="0">
            <slug>plante-et-luminaire</slug>
            <description>Plantes et luminaires !</description>
            <debut>00.08.27.300</debut>
            <fin>00.09.46.600</fin>
        </extrait>
</source>
~~~

Dans cet exemple, la vidéo source `le-tribunal-des-bureaux-1.mp4` n'a pas encore d'extraits. L'extrait `Plantes et luminaires !` sera extrait de la vidéo source `le-tribunal-des-bureaux-2.mp4`.

#### Supprimer un extrait

Il suffit de supprimer l'élément `<extrait>...</extrait>` correspondant. Si on reprend l'exemple précédent après suppression

~~~xml
<source name="le-tribunal-des-bureaux-1.mp4">
</source>
<source name="le-tribunal-des-bureaux-2.mp4">
</source>
~~~

L'extrait `Plantes et luminaires !` a été retiré de la source `le-tribunal-des-bureaux-2.mp4`. Au prochain passage du programme l'extrait sera supprimé du dossier `extraits`.

### 5. Gestion du compte du Twitter Bot

A venir...

## Formattage des noms

### Fichier vidéo *source*

Voir [ici](sources/README.md)

### Fichier vidéo *extrait*

Voir [ici](extraits/README.md)

### Timecode

Les timecodes (instant de début ou de fin de l'extrait) doivent être formattés au format `hh.mm.ss.lll` avec 

- `h` l'heure
- `m` la minute
- `s` la seconde
- `l` la miliseconde

Ils doivent être compris entre `00.00.00.000` et la durée totale de la vidéo.

## Gestion des fichiers sources et des extraits

### Fichiers sources

Les fichiers *sources* sont les vidéos téléchargées depuis youtube entières et servent de source aux extraits. Elles se trouvent dans le dossier `sources`.

Les fichiers *sources* **doivent respecter [un format](sources/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

### Extraits

Les *extraits* sont les extraits vidéos des [sources](#fichiers-sources). Ils sont générés automatiquement à partir des informations fournies dans le [fichier source](#le-fichier-source). Ils se trouvent dans le dossier `extraits`.

Les fichiers *extraits* **doivent respecter [un format](extraits/README.md#format-du-nom)** sinon elles finiront pas être **supprimées automatiquement**.

Un extrait doit faire **au moins 1 seconde**, sinon il ne sera pas généré et une exception sera levée.

### Besoins pour une interface de *cut*

Le développement d'une petite application web en surcouche de l'édition du fichier source parait rapidement indispensable pour plusieurs raisons:

- éviter de mauvaises manipulations sur le fichier source
- faciliter l'ajout d'extrait sans erreur
- prévisualiser l'extrait avant de l'enregistrer (un bon cut se fait à la miliseconde)
- pouvoir visionner la vidéo sur la page où on ajuste les timecodes pour éditer son cut de manière plus agréable


Besoins identifiés :

- preview de la vidéo source
- indique le timecode a tout moment
- timecode entrée et de sortie éditables
- manipuler les timecodes via une interface graphique sur le player (luxe)
- plusieurs cut dans un seul fichier via des marqueurs avec label des extraits, en un clic exporter tous les marqueurs (luxe)
- *normaliser le volume* avec une valeur par défaut (voir ré-encodage) au post-montage. Le volume de la piste audio est défini par défaut par une métadonnée. Il faut que la normalisation se fasse sur le cut et non sur la vidéo entière (normalisation est une fonction de la piste entière, analyse les pics/creux de volume sur tout le volume et essaie de normaliser à partir de ça. Donc attention à ça)

## Ressources

- [Twitter API](https://developer.twitter.com/en/docs/twitter-api)
- [Rate Limits de la Twitter API](https://developer.twitter.com/en/docs/twitter-api/rate-limits)
- [Playlist du tribunal des bureaux](https://www.youtube.com/watch?v=YglE-FnSd3g&list=PLDN-m4HWH8MBKJLYIK-80qJBBkVJbPo9p)
- [Youtube clips features](https://www.youtube.com/watch?v=A63imEmP_-I)
- [YouTube Video Downloader Script in PHP](https://www.phpzag.com/php-youtube-video-downloader-script/)
- [jdownloader](https://jdownloader.org/), outil open-source de téléchargement en flux continu
- [filezilla](https://filezilla-project.org/), client ftp open-source
- [lostlesscut](https://github.com/mifi/lossless-cut), le couteau suisse de l'édition vidéo/audio pour cut la vidéo d'origine