<?php
if(file_exists(__DIR__ . '/../vendor/autoload.php')) {
    $loader = include __DIR__ . '/../vendor/autoload.php';
} elseif(file_exists(__DIR__ . '/../../../vendor/autoload.php')) {
    $loader = include __DIR__ . '/../../../vendor/autoload.php';
} else {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}
$loader->add('Zf2ModuleInstallTest', __DIR__);
$loader->add('Composer\Installers\Test', __DIR__ . '/../vendor/composer/installers/tests/');
