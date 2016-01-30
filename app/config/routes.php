<?php

$router = new \Phalcon\Mvc\Router(false);
$router->removeExtraSlashes(true);

$router->add('/',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'socialSample',
		'action' => 'home'
	));

/* 404 */
$router->add('/404',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'socialSample',
        'action' => 'notFound'
    ));

/* Auth */
$router->add('/login',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'auth',
		'action' => 'login'
	));

$router->add(
	'/logout',
	array('namespace'  => 'app\controllers',
	      'controller' => 'auth',
	      'action'     => 'logout')
);

$router->add(
	'/login/auth',
	array('namespace'  => 'app\controllers',
	      'controller' => 'auth',
	      'action'     => 'auth')
);

$router->add('/signup',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'auth',
		'action' => 'signup'
	));

$router->add('/recover',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'auth',
		'action' => 'recover'
	));



/* Home */
$router->add('/home',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'home',
		'action' => 'fire'
	));

/* Notifications */
$router->add('/notifications',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'notification',
		'action' => 'fire'
	));



/* BEGIN OF PROFILE NESTED VIEWS */

$router->add('/view/photos/item/edit',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'editPhotoItemModal'
    ));

$router->add('/view/photos/photo-album-upload',
array(
    'namespace' => 'app\controllers',
    'controller' => 'photo',
    'action' => 'photoAlbumUploadModal'
));

$router->add('/view/photos/photo-upload',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'photoUploadModal'
    ));

$router->add('/view/feed-item-popover',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'feed',
        'action' => 'feedItemPopover'
    ));

$router->add('/view/feed-item-privacy',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'feed',
        'action' => 'feedItemPrivacyModal'
    ));

$router->add('/view/photos/item',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'photoItemModal'
    ));

$router->add('/view/photo-plus-popover',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'photoPlusPopover'
    ));

/* Settings Menu */
$router->add('/view/settings-menu-popover',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'settings',
        'action' => 'menu'
    ));

/* Photo */
$router->add('/view/profile/{uid}/photos',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'fire',
        'uid' => 2
    ));

$router->add('/view/profile/{uid}/photos/albums',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'albums'
    ));

$router->add('/view/profile/{uid}/photos/albums/:id',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'fire'
    ));

$router->add('/view/profile/{uid}/photos/all',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'allPhotos',
        'uid' => 2
    ));

$router->add('/view/profile/{uid}/about',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'profile',
        'action' => 'about',
        'uid' => 2
    ));

$router->add('/view/profile/{uid}/friends',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'friend',
        'action' => 'fire',
        'uid' => 2
    ));

$router->add('/view/profile/{uid}/timeline',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'feed',
        'action' => 'timeline',
        'params' => 2
    ));

$router->add('/view/feeds/item/{id}',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'feed',
        'action' => 'feedItemModal'
    ));

/* END OF NESTED VIEWS */

/* Photo */
$router->add('/photos/:params',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'photo',
        'action' => 'fire',
        'params' => 1
    ));

/* Profile */
$router->add('/profile/{uid}/:params',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'profile',
        'action' => 'fire',
        'params' => 2
    ));

/* Message */
$router->add('/messages',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'message',
		'action' => 'fire'
	));

$router->add('/messages/{uid}',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'message',
        'action' => 'fire'
    ));

/* Feed Comments */
$router->add('/feeds/item/{id}',
    array(
        'namespace' => 'app\controllers',
        'controller' => 'feed',
        'action' => 'feedItem'
    ));

/* Journal (Bonus) */
$router->add('/journals',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'journal',
		'action' => 'fire'
	));

/* Settings */
$router->add('/settings',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'settings',
		'action' => 'fire'
	));

/* Photo Upload */
$router->add('/upload/image/:params',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'upload',
		'action' => 'image',
		'params' => 1
	));




/* Admin */
$router->add('/cp/:params',
	array(
		'namespace' => 'app\controllers',
		'controller' => 'cp',
		'action' => 'fire',
		'params' => 1
	));

$router->notFound(array(
    'namespace' => 'app\controllers',
    'controller' => 'socialSample',
    'action' => 'notFound'));

return $router;