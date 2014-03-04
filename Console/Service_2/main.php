<?php

	
	define(DS, DIRECTORY_SEPARATOR);

	require 'Config'.DS.'bootstrap.php';
	require 'Lib'.DS.'SendingService.php';
	require 'Lib'.DS.'Factory.php';

	$main = new SendingService();	
	$main->init();
?>
