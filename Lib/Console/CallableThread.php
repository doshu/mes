<?php

	class CallableThread extends Thread {
		
		private $__toRun;
		private $__data;
		
		public function __construct(callable $callable, $data = null) {
			$this->__toRun = $callable;
			$this->__data = $data;
		}
		
		public function run() {
			$callable = $this->__toRun;
			$callable($this->__data);
		}
	}

?>
