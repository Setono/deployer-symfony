<?php

declare(strict_types=1);

namespace Setono\Deployer\Symfony;

use function Deployer\commandExist;
use Deployer\Exception\Exception;
use function Deployer\get;
use function Deployer\has;
use function Deployer\locateBinaryPath;
use function Deployer\run;
use function Deployer\set;
use function Deployer\task;
use function Deployer\test;
use function sprintf;

set('symfony_install_binary', false);
set('symfony_binary_install_location', '/usr/local/bin');

set('bin/symfony', function () {
    if (commandExist('symfony')) {
        /** @var string $binary */
        $binary = locateBinaryPath('symfony');

        if (test(sprintf('[ -w %s ]', dirname($binary)))) {
            run(sprintf('%s self:update --yes -q', $binary));
        }

        return $binary;
    }

    if (get('symfony_install_binary') === false) {
        throw new Exception('The symfony binary does not exist on your server and the parameter symfony_install_binary equals false which means this Deployer recipe won\'t install it either. Either set symfony_install_binary to true or install it manually on the server.');
    }

    run('wget https://get.symfony.com/cli/installer -O - | bash');
    run('mv ~/.symfony/bin/symfony {{symfony_binary_install_location}}/symfony');

    return locateBinaryPath('symfony');
});

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
