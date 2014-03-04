<?php

	// se l'utente è loggato collego / all'index, altrimenti alla pagina di login
	App::uses('AuthComponent', 'Controller/Component');
	
	if(!is_null(AuthComponent::user()))
		Router::connect('/', array('controller' => 'mails', 'action' => 'dashboard'));
	else
		Router::connect('/', array('controller' => 'users', 'action' => 'login'));

	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	Router::connect('/open_me', array('controller' => 'recipients', 'action' => 'openMe'));
	Router::connect('/brokenLink', array('controller' => 'pages', 'action' => 'display', 'brokenLink'));
	Router::connect('/unsubscribe', array('controller' => 'members', 'action' => 'unsubscribe'));
	Router::connect('/unsubscribeResult', array('controller' => 'pages', 'action' => 'display', 'unsubscribeResult'));

	Router::parseExtensions('json');
	
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
