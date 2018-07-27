#!/bin/sh

## Génération des assets css et js
php bin/console assets:install;
php bin/console assetic:dump;

## Supression du cache de prod
php bin/console cache:clear --env=prod;

