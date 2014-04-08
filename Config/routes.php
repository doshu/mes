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

	Router::connect(
		'/open_me_:recipient_:key_.png',
		array('controller' => 'recipients', 'action' => 'openMe'),
		array(
		    'pass' => array('recipient', 'key'),
		    'recipient' => '[0-9]+',
		    'key' => '.*[^_]'
		)
	);
	
	Router::connect(
		'/open_me_link_:recipient_:key_:uri',
		array('controller' => 'recipients', 'action' => 'openMe'),
		array(
		    'pass' => array('recipient', 'key', 'uri'),
		    'recipient' => '[0-9]+',
		    'key' => '.*[^_]',
		    'uri' => '.*'
		)
	);
	
	Router::connect(
		'/open_me_image_:recipient_:key_:uri_:fromimage.:ext',
		array('controller' => 'recipients', 'action' => 'openMe'),
		array(
		    'pass' => array('recipient', 'key', 'uri', 'fromimage', 'ext'),
		    'recipient' => '[0-9]+',
		    'key' => '.*[^_]',
		    'uri' => '.*',
		    'fromimage' => '[0-9]+',
		    'ext' => '.*'
		)
	);		
	
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
