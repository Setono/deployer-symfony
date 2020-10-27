<?php

declare(strict_types=1);

namespace Setono\Deployer\DotEnv;

use function Deployer\commandExist;
use function Deployer\locateBinaryPath;
use function Deployer\run;
use function Deployer\set;
use function Deployer\task;

task('symfony:binary', static function (): void {
    set('bin/symfony', function () {
        if (commandExist('symfony')) {
            $binary = locateBinaryPath('symfony');

            // todo: Outcomment this when this issue is fixed: https://github.com/symfony/cli/issues/352
            // run(\Safe\sprintf('%s self:update --no-interaction'));

            return $binary;
        }

        run('wget https://get.symfony.com/cli/installer -O - | bash');

        return locateBinaryPath('symfony');
    });
})->desc('Checks if the symfony binary is installed. If not, it installs it');
