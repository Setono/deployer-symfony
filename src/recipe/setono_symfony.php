<?php

declare(strict_types=1);

namespace Setono\Deployer\DotEnv;

use function Deployer\before;

require_once 'task/setono_symfony.php';

before('deploy:prepare', 'symfony:binary');
