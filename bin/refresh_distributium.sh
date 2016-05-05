#!/bin/bash

PATH=/var/www/distributium
/usr/bin/php ${PATH}/app/console cache:clear --env=prod
/usr/bin/php ${PATH}/app/console cache:clear --env=dev
/bin/chmod -R 777 ${PATH}/app/{cache,logs}

/usr/bin/php ${PATH}/app/console sonata:page:create-snapshots --site=all

