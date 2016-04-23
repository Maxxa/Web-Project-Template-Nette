<?php

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

//$configurator->setDebugMode('23.75.345.200'); // enable for your remote IP
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');

if ($configurator->isDebugMode()) {
	$configurator->addConfig(__DIR__ . '/config/developmentDB.neon');
} else {
	$configurator->addConfig(__DIR__ . '/config/productionDB.neon');
}

$container = $configurator->createContainer();

return $container;
