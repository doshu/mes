<?php


	abstract class FThread {
	
		private $__fifo;
		private $__fifoFp;
		public $whoAmI;
		
		private function __beforeStart() {
			$this->__fifo = $this->__createFifoName();
			if(!posix_mkfifo(FIFO_DIR.$this->__fifo, 0777)) {
				throw new Exception('Cannot create fifo');
			}
			$this->__fifoFp = fopen(FIFO_DIR.$this->__fifo, 'a+');
		}
		
		private function __afterFork() {
			//$this->__fifoFp = fopen(FIFO_DIR.$this->__fifo, 'a+');
			if(!$this->__fifoFp) {
				throw new Exception('Cannot open fifo');
			}
		}
		
		private function __afterEnd() {
			$this->notify();
			fclose($this->__fifoFp);
			unlink(FIFO_DIR.$this->__fifo);
		}
		
		public function isWaiting() {
			return true;
		}
		
		public function wait() {
			if(is_resource($this->__fifoFp)) {
				echo 'ricevuto ';
				var_dump(fread($this->__fifoFp, 1));
			 }
		}
		
		public function notify() {
			echo 'inviato ';
			fwrite($this->__fifoFp, '1', 1);
		}
		
		public function start() {
			$this->__beforeStart();
			$pid = pcntl_fork();
			$this->__afterFork();
			if($pid == 0) {
				$this->whoAmI = 'child';
				$this->run();
				$this->__afterEnd();
				exit;
			}
			else {
				$this->whoAmI = 'parent';
			}
			
		}
		
		private function __createFifoName() {
			do {
				$fifo = md5(rand().time());
			} while(file_exists(FIFO_DIR.$fifo));
			return $fifo;
		}
		
	}

?>
