#!/bin/bash

php app/console cache:clear --env=dev
php app/console cache:clear --env=prod
chmod -R 777 app/{cache,logs}
