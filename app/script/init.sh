#!/bin/sh

## Supression des bases de donnée
php bin/console doctrine:database:drop --force;
php bin/console doctrine:database:drop --force --connection=db_dev;

## Création des bases de donnée
php bin/console doctrine:database:create;
php bin/console doctrine:database:create --connection=db_dev;

## Remplissage des tables de la base de donnée
php bin/console doctrine:migrations:migrate;
php bin/console doctrine:migrations:migrate --db=db_dev;

## Insertion de donné factise dans la base de donnée de Production
php bin/console doctrine:fixtures:load --fixtures=src/ToolsBundle/DataFixtures/ORM/ProdFixtures.php;

# Generate doc informations
sudo php phpDocumentor.phar -d src -t web/docs/;