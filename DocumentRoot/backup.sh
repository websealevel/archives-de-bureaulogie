#!/bin/bash

#Déclarations
webpath=apps/archives-de-bureaulogie.fr/DocumentRoot

#Credentials db (les recuperer dans le .env)

#Prefix backup
printf -v prefix '%(%Y-%m-%d)T' -1 ;

#Backup db
db_dump_file="${prefix}-prod.sql";

#Backup extrais.xml/extraits.dtd

#Créer une archive contenant les 3 fichiers

#Déplacer l'archive dans le dossier de backup