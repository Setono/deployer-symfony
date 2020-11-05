<?php

declare(strict_types=1);

namespace Setono\Deployer\DotEnv;

use function Deployer\commandExist;
use function Deployer\has;
use function Deployer\locateBinaryPath;
use function Deployer\parse;
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
    parse('{{bin/symfony}}');
})->desc('This task will parse the {{bin/symfony}} parameter which makes sure the Symfony binary is installed');

/**
 * This task relies on the 'previous_release' being set. It should therefore hook into the flow like so:
 * after('deploy:release', 'symfony:messenger:stop');
 */
task('symfony:messenger:stop', static function (): void {
    if (!has('previous_release')) {
        return;
    }

    run('{{bin/php}} {{previous_release}}/bin/console messenger:stop-workers');
})->desc('Stops Messenger workers');
