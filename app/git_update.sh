#!/bin/bash


git pull origin master
app/distributium_cache_clear.sh
php app/console assetic:dump --env=dev
php app/console assetic:dump --env=prod
