<?php

	ini_set("log_errors", 1);
	ini_set("error_log", "/tmp/php-error.log");
	ini_set('display_errors', 1);
	ini_set('error_reporting', E_ALL);

	define('LOGFILE', dirname(__FILE__).DS.'..'.DS.'Var'.DS.'Log'.DS.'sending_service.log');
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__).DS.'..'.DS);
	date_default_timezone_set('UTC');
	define('ATTACHMENT_DIR', '/var/www/mes/attachment/');
	define('PHP_BIN', '/var/www/mes/Console/Service_2/php');
	define('FIFO_DIR', dirname(__FILE__).DS.'..'.DS.'Var'.DS.'Fifo'.DS);
	define('SERVICE_URL', 'http://www.powamail.tk/mes/open_me');
	define('FAKE_IMAGE_URL', SERVICE_URL);
	define('SENDING_POOL', dirname(dirname(__FILE__)).DS.'Var'.DS.'sending_pool');
	define('UNSUSCRIBE_LINK', 'http://www.powamail.tk/mes/unsubscribe?recipient=%s&key=%s&sending=%s&redirect=%s');
	//define('FAKE_IMAGE_URL', 'http://127.0.0.1/mes/check_opened.jpg');
	
	define('OPEN_ME_LINK', 'http://www.powamail.tk/mes/open_me_link_%s_%s_%s');
	define('OPEN_ME_IMAGE', 'http://www.powamail.tk/mes/open_me_image_%s_%s_%s_1.%s');
	define('OPEN_ME_FAKE', 'http://www.powamail.tk/mes/open_me_%s_%s_.png');
?>
