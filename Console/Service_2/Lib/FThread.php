<?php


	abstract class FThread {
	
		private $__fifo;
		private $__fifoFp;
		private $__fifoFpClient;
		public $whoAmI;
		
		private function __beforeStart() {
			$this->__fifo = $this->__createFifoName();
		}
		
		private function __afterForkParent() {

			$this->__fifoFp = stream_socket_server('unix://'.FIFO_DIR.$this->__fifo);
			if(!$this->__fifoFp) {
				throw new Exception('Cannot create socket server');
			}
			$this->__fifoFp = stream_socket_accept($this->__fifoFp, 20);
			if(!$this->__fifoFp) {
				throw new Exception('Cannot accept socket connection');
			}
		}
		
		private function __afterForkChild() {
			//il parent potrebbe non aver ancora aperto il server, ma essndo basato su unix socket se il file non esiste va in errore
			//attendo qualche secondo che venga creato il file
			$wait = 5; //aspetto massimo 5 secondi
			$start = time();
			do {
				$now = time();
			} while(!file_exists(FIFO_DIR.$this->__fifo) && $now - $start < $wait);
			$this->__fifoFp = stream_socket_client('unix://'.FIFO_DIR.$this->__fifo, $errno, $errstr, 20);
			if(!$this->__fifoFp) {
				throw new Exception('Cannot create socket client');
			}
		}
		
		private function __afterEnd() {
			if(is_resource($this->__fifoFp)) {
				fclose($this->__fifoFp);
			}
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
			
			if($pid == -1) {
				throw new Exception('Cannot fork');
			}
			elseif($pid == 0) {
				$this->whoAmI = 'child';
				try {
					$this->__afterForkChild();
					$this->run();
					$this->__afterEnd();
					exit;
				}
				catch (Exception $e) {
					$this->onStartChildFail($e); //on start child fail (rimettere invio a waiting)
					exit;
				}
			}
			else {
				$this->whoAmI = 'parent';
				try {
					$this->__afterForkParent();
				}
				catch (Exception $e) {
					$this->onAfterForkParentFail($e, $pid);//on after fork parent fail (killare il child e rimettere invio a waiting)
					throw $e;
				}
				
				return $pid; //mettere associato all'invio nel pool e creare script per eventuale kill di un invio.
			}
			
		}
		
		public function onAfterForkParentFail($e, $childPid) {
			posix_kill($childPid, SIGTERM);
		}
		
		public function onStartChildFail($e) {
			die($e->getMessage);
		}
		
		private function __createFifoName() {
			do {
				$fifo = md5(rand().time());
			} while(file_exists(FIFO_DIR.$fifo));
			return $fifo;
		}
		
		
		public function __destruct() {
			if(is_resource($this->__fifoFp)) {
				fclose($this->__fifoFp);
			}
			@unlink(FIFO_DIR.$this->__fifo);
		}
		
	}

?>
