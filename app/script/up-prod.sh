#!/bin/sh

## Supression des bases de donnée
php bin/console doctrine:database:drop --force;

## Création des bases de donnée
php bin/console doctrine:database:create;

## Remplissage des tables de la base de donnée
php bin/console doctrine:migrations:migrate;

php bin/console doctrine:fixtures:load --fixtures=src/ToolsBundle/DataFixtures/ORM/ProdFixtures.php;
