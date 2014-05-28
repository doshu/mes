<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models', '/next/path/to/models'),
 *     'Model/Behavior'            => array('/path/to/behaviors', '/next/path/to/behaviors'),
 *     'Model/Datasource'          => array('/path/to/datasources', '/next/path/to/datasources'),
 *     'Model/Datasource/Database' => array('/path/to/databases', '/next/path/to/database'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions', '/next/path/to/sessions'),
 *     'Controller'                => array('/path/to/controllers', '/next/path/to/controllers'),
 *     'Controller/Component'      => array('/path/to/components', '/next/path/to/components'),
 *     'Controller/Component/Auth' => array('/path/to/auths', '/next/path/to/auths'),
 *     'Controller/Component/Acl'  => array('/path/to/acls', '/next/path/to/acls'),
 *     'View'                      => array('/path/to/views', '/next/path/to/views'),
 *     'View/Helper'               => array('/path/to/helpers', '/next/path/to/helpers'),
 *     'Console'                   => array('/path/to/consoles', '/next/path/to/consoles'),
 *     'Console/Command'           => array('/path/to/commands', '/next/path/to/commands'),
 *     'Console/Command/Task'      => array('/path/to/tasks', '/next/path/to/tasks'),
 *     'Lib'                       => array('/path/to/libs', '/next/path/to/libs'),
 *     'Locale'                    => array('/path/to/locales', '/next/path/to/locales'),
 *     'Vendor'                    => array('/path/to/vendors', '/next/path/to/vendors'),
 *     'Plugin'                    => array('/path/to/plugins', '/next/path/to/plugins'),
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */

CakePlugin::load(array('FileManager' => array('bootstrap' => true)));
 
Configure::write('Dispatcher.filters', array(
	//'HelloWorldFilter',
	'AssetDispatcher',
	'CacheDispatcher'
));

Configure::write('Exception.redirectProduction', array('plugin' => false, 'controller' => 'mails', 'action' => 'dashboard'));
Configure::write('Exception.errorRedirectProduction', array('controller' => 'pages', 'action' => 'error'));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));


function timeToWords($seconds) {

	$els = array(
		'd' => 60*60*24,
		'h' => 60*60,
		'm' => 60,
		's' => 1
	);
	
	$translated_p = array(
		'd' => __('Giorni'),
		'h' => __('Ore'),
		'm' => __('Minuti'),
		's' => __('Secondi'),
	);
	
	$translated_s = array(
		'd' => __('Giorno'),
		'h' => __('Ora'),
		'm' => __('Minuto'),
		's' => __('Secondo'),
	);
	
	if($seconds == 0) {
		return '0 '.__('Secondi');
	}
	
	$result = array();
	foreach($els as $el => $div) {
		$res = (int)($seconds/$div);
		$result[$el] = $res;
		$seconds -= $res*$div;
	}
	$string = array();
	foreach($result as $el => $val) {
		if($val > 0) {
			$string[] = $val.' '.($val > 1?$translated_p[$el]:$translated_s[$el]);
		}
	}
	
	return implode(' ', $string);
}


function human_filesize($bytes) {
    $size = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return number_format(($bytes / pow(1024, $factor)), 2).' '.$size[$factor];
}

function is_between($n, $min, $max, $limit = true) {
	if($limit) {
		return ($n >= $min && $n <= $max);
	}
	else {
		return ($n > $min && $n < $max);
	}
}

Configure::write('check_cookie_action', array('plugin' => null, 'controller' => 'users', 'action' => 'checkCookie'));

define('SPAM_LIMIT_OK', 30);
define('SPAM_LIMIT_WARNING', 60);


define('SERVICE_URL', 'http://devel.powamail.tk/mes/open_me');
define('FAKE_IMAGE_URL', SERVICE_URL);
define('UNSUSCRIBE_LINK', 'http://devel.powamail.tk/mes/unsubscribe?recipient=%s&key=%s&sending=%s&redirect=%s');
define('OPEN_ME_LINK', 'http://devel.powamail.tk/mes/open_me_link_%s_%s_%s');
define('OPEN_ME_IMAGE', 'http://devel.powamail.tk/mes/open_me_image_%s_%s_%s_1.%s');
define('OPEN_ME_FAKE', 'http://devel.powamail.tk/mes/open_me_%s_%s_.png');
