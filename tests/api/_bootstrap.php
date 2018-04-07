<?php

// executes the "php bin/console doctrine:schema:update" command
passthru(
    sprintf('php "%s/../../bin/console" doctrine:database:create --if-not-exists --env=test -q', __DIR__)
);

// executes the "php bin/console doctrine:schema:update" command
passthru(
    sprintf('php "%s/../../bin/console" doctrine:schema:update --force --env=test -q', __DIR__)
);
