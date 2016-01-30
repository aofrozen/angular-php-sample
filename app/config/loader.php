<?php


$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
	array(
		'app\controllers' => APP_PATH . $config->application->controllersDir,
		'app\models'      => APP_PATH . $config->application->modelsDir,
		'app\library'     => APP_PATH . $config->application->libraryDir,
		'app\plugins'     => APP_PATH . $config->application->pluginsDir
	)
);
$loader->register();