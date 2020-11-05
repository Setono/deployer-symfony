<?php

declare(strict_types=1);

namespace Setono\Deployer\Symfony;

use function Deployer\after;
use function Deployer\before;
use PackageVersions\Versions;

require_once 'task/setono_symfony.php';

before('deploy:prepare', 'symfony:binary');

try {
    // this call will throw an exception if the package isn't installed
    Versions::getVersion('symfony/messenger');

    after('deploy:release', 'symfony:messenger:stop');
} catch (\OutOfBoundsException $e) {
}
