<?php

declare(strict_types=1);

namespace Setono\Deployer\DotEnv;

use function Deployer\commandExist;
use function Deployer\locateBinaryPath;
use function Deployer\run;
use function Deployer\set;
use function Deployer\task;
use function Safe\sprintf;

set('bin/symfony', function () {
    if (commandExist('symfony')) {
        $binary = locateBinaryPath('symfony');

        run(sprintf('%s self:update --yes -q', $binary));

        return $binary;
    }

    run('wget https://get.symfony.com/cli/installer -O - | bash');
    run('mv ~/.symfony/bin/symfony /usr/local/bin/symfony');

    return locateBinaryPath('symfony');
});

task('symfony:binary', static function (): void {
    run('{{bin/symfony}} help > /dev/null');
})->desc('This is a just a task you can run to make sure the binary is installed');
