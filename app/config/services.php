<?php

$di = new \Phalcon\DI\FactoryDefault();


$di->set('view', function () {
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir(APP_PATH . '/app/views/');
	$view->registerEngines(array(".volt" => 'Phalcon\Mvc\View\Engine\Volt'));
	return $view;
});

	/**
	 * Config
	 */
$di->set('config', function () use ($config) {
	return $config;
});
/**
 * Model Manager
 */
$di->set('modelsManager', function () {
	return new Phalcon\Mvc\Model\Manager();
});
/**
 * Flash
 */
$di->set('flash', function () {
	$flash = new \Phalcon\Flash\Direct(array(
		'error'   => 'alert alert-danger',
		'success' => 'alert alert-success',
		'notice'  => 'alert alert-info',
	));
	return $flash;
});
/**
 * Navigation
 */

$di->set('navigation', function () {
	$navigation = new app\library\navigation\navigation();
	return $navigation;
});
/**
 * URL
 */
$di->set('url', function () {
	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri('/');
	$url->setStaticBaseUri('/');
	return $url;
});
/**
 * Encryption
 */
$di->set('crypt', function () use ($config) {
	$crypt = new Phalcon\Crypt();
	$crypt->setKey($config->encryption->key);
	return $crypt;
});
/**
 * Cookie
 */
$di->set('cookies', function() {
    $cookies = new Phalcon\Http\Response\Cookies();
    $cookies->useEncryption(false);
    return $cookies;
});

/**
 * Database
 */
$di->set('mongo', function () use ($config) {
	$mongo = new MongoClient('mongodb://127.0.0.1:27017/');
	return $mongo->selectDB($config->mongodb->name);
}, true);
$di->set('collectionManager', function () {
	return new Phalcon\Mvc\Collection\Manager();
}, true);

/**
 * Dispatcher
 */

/*
$di->set('dispatcher', function () use ($di) {
	//Obtain the standard eventsManager from the DI
	$eventsManager = $di->getShared('eventsManager');

	//Instantiate the Security plugin
	$signupSetup = new \app\plugins\signupSetup($di);

	//Listen for events produced in the dispatcher using the Security plugin
	$eventsManager->attach('dispatch', $signupSetup);

	$dispatcher = new Phalcon\Mvc\Dispatcher();

	//Bind the EventsManager to the Dispatcher
	$dispatcher->setEventsManager($eventsManager);
	return $dispatcher;
});
*/
/*
 * Auth (social networking)
 */
$di->set('auth', function () use ($authConfig) {
	$hybridauth = new Hybrid_Auth($authConfig);
	return $hybridauth;
});

/*
 * Uploader
 */
$di->set('uploader', function () {
	return new \Uploader\Uploader();
});

/*
 * Mobile Detection
 */

$di->set(
	'detect',
	function() {
		return new Mobile_Detect();
	}
);

/*
 * Common
 */
$di->set(
    'c',
    function(){
        return new \app\library\common\common();
    }
    , true
);

/*
 * Embedly
 */
$di->set(
    'embedly',
    function(){
        return new \Embedly\Embedly(array('key' => 'c1497a8be9e443a5a27f2f6b221d1568', 'user_agent' => 'Mozilla/5.0 (compatible; mytestapp/1.0)'));
    }
    , true
);

/*
 * Hash Ids
 */
$di->set(
    'hashids',
    function() use($config){
        return new \Hashids\Hashids($config->hashids->key);
    }, true
);

/**
 * Router
 */

$di->set(
	'router',
	function () {
		return include APP_PATH . "/app/config/routes.php";
	},
	true
);

