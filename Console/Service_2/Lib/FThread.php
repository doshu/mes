<?php


	abstract class FThread {
	
		private $__fifo;
		private $__fifoFp;
		private $__fifoFpClient;
		public $whoAmI;
		
		private function __beforeStart() {
			$this->__fifo = $this->__createFifoName();
			/*
			if(!posix_mkfifo(FIFO_DIR.$this->__fifo, 0777)) {
				throw new Exception('Cannot create fifo');
			}
			*/
			//$this->__fifoFp = fopen(FIFO_DIR.$this->__fifo, 'a+');
			
			
		}
		
		private function __afterForkParent() {
			//$this->__fifoFp = fopen(FIFO_DIR.$this->__fifo, 'a+');
			$this->__fifoFp = stream_socket_server('unix://'.FIFO_DIR.$this->__fifo);
			$this->__fifoFp = stream_socket_accept($this->__fifoFp);
			if(!$this->__fifoFp) {
				throw new Exception('Cannot open fifo');
			}
		}
		
		private function __afterForkChild() {
			//$this->__fifoFp = fopen(FIFO_DIR.$this->__fifo, 'a+');
			$this->__fifoFp = stream_socket_client('unix://'.FIFO_DIR.$this->__fifo);
			if(!$this->__fifoFp) {
				throw new Exception('Cannot open fifo');
			}
		}
		
		private function __afterEnd() {
			fclose($this->__fifoFp);
			@unlink(FIFO_DIR.$this->__fifo);
		}
		
		public function isWaiting() {
			return true;
		}
		
		public function wait() {

			if(is_resource($this->__fifoFp)) {
				stream_get_line($this->__fifoFp, 1);
			}
			
		}
		
		public function notify() {
			if(is_resource($this->__fifoFp)) {
				fwrite($this->__fifoFp, '1', 1);
			}
		}
		
		public function start() {
			$this->__beforeStart();
			$pid = pcntl_fork();
			if($pid == 0) {
				$this->whoAmI = 'child';
				$this->__afterForkChild();
				$this->run();
				$this->__afterEnd();
				exit;
			}
			else {
				$this->whoAmI = 'parent';
				$this->__afterForkParent();
			}
			
		}
		
		private function __createFifoName() {
			do {
				$fifo = md5(rand().time());
			} while(file_exists(FIFO_DIR.$fifo));
			return $fifo;
		}
		
		
		public function __destruct() {
			fclose($this->__fifoFp);
			@unlink(FIFO_DIR.$this->__fifo);
		}
		
	}

?>
