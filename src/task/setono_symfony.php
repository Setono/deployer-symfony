<?php

declare(strict_types=1);

namespace Setono\Deployer\DotEnv;

use function Deployer\task;

task('symfony:binary', static function (): void {

})->desc('Checks if the symfony binary is installed. If not, it installs it');
