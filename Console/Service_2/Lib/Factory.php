<?php

	class Factory {
		
		private static $__map = array();
		
		public static function getInstance($class) {
			require_once $class.'.php';
			$className = explode('/', $class);
			$className = $className[count($className)-1];
			return new $className();
		}
		
		
		public static function getSingleton($class) {
			if(!isset($this->__map[$class])) {
				$this->__map[$class] = $this->getInstance($class);
			}
			return $this->__map[$class];
		}
	}

?>
