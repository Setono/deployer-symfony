<?php

declare(strict_types=1);

namespace Setono\Deployer\Symfony;

use Composer\InstalledVersions;
use function Deployer\after;

require_once 'task/setono_symfony.php';

if (InstalledVersions::isInstalled('symfony/messenger')) {
    after('deploy:release', 'symfony:messenger:stop');
}
