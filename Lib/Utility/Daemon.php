<?php
	class Daemon {
	
		public static $pid;
		
		public static function daemonize() {
			self::$pid = pcntl_fork();
			if(self::$pid)
				exit();
			else
				return self::$pid = getmypid();
		}	
	}
?>
