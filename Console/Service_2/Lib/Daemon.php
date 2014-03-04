<?php

	/**
	 * Classe che trasforma il processo corrente in un demone
	 */

	class Daemon {
	
		public static $pid;
		
		public static function daemonize() {
			$pid = pcntl_fork();
			if($pid)
				exit();
			else
				return self::$pid = getmypid();
		}	
	}
?>
