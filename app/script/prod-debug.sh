#!/bin/sh

## Génération des assets css et js
php bin/console assets:install;
php bin/console assetic:dump;

## Generation de la doc
sudo php phpDocumentor.phar run -d src -t web/docs/

## Supression du cache de prod
php bin/console cache:clear --env=prod;

