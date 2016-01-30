<?php

//(new \Phalcon\Debug())->listen();
//error_reporting(E_ALL);
/*
if($_SERVER['REMOTE_ADDR'] !== '104.48.242.67')
    {
        header("location: http://socialSample.net");
        exit;
    }
*/
//ini_set("display_errors", "on");
require '../vendor/autoload.php';
$config = new Phalcon\Config\Adapter\Ini("../app/config/config.ini");
$authConfig = require '../app/config/authConfig.php';
define('APP_PATH', realpath('..'));
try {
	/**
	 * MC Loader
	 */
	require APP_PATH.'/app/config/loader.php';
	/*
	 * Services
	 */
	require APP_PATH.'/app/config/services.php';
	$ajaxCheck = explode('/', $_SERVER['REQUEST_URI']);
	if($ajaxCheck[1] == 'ajax')
	{
		require APP_PATH.'/public/ajax.php';
	}else{
		$app = new \Phalcon\Mvc\Application($di);
		echo $app->handle()->getContent();
	}

} catch(\Phalcon\Exception $e) {
	header("HTTP/1.0 404 Not Found");
	echo get_class($e), ": ", $e->getMessage(), "\n";
	echo " File=", $e->getFile(), "\n";
	echo " Line=", $e->getLine(), "\n";
	echo $e->getTraceAsString();

} catch(Exception $e){
	echo $e->getMessage()."<br>";
	echo $e->getLine()."<br>";
	echo $e->getFile()."<br>";
	echo $e->getTrace()."<br>";
}
//EDITED
