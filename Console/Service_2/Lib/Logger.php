<?php

	class Logger {
		
		private $__path = null;
		
		public function __construct($path) {
			$this->__path = $path;
		}
		
			
		public function info($msg) {
			file_put_contents($this->__path, 'Info: '.$msg."\n", FILE_APPEND);
		}
		
		public function error($msg) {
			file_put_contents($this->__path, 'Error: '.$msg."\n", FILE_APPEND);
		}
		
		public function warning($msg) {
			file_put_contents($this->__path, 'Warning: '.$msg."\n", FILE_APPEND);
		}
	}

?>
