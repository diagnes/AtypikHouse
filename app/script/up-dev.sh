#!/bin/sh

## Supression des bases de donnée
php bin/console doctrine:database:drop --force --connection=db_dev;

## Création des bases de donnée
php bin/console doctrine:database:create --connection=db_dev;

## Remplissage des tables de la base de donnée
php bin/console doctrine:migrations:migrate --db=db_dev;