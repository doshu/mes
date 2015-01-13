<?php

	require_once 'Output.php';
	require_once 'Logger.php';
	require_once 'Daemon.php';
	require_once 'SendingActivity.php';
	
	require_once 'Model'.DS.'Sending.php';

	class SendingService {
		
		public $Output;
		public $Logger;
		public $Sending;
		public $SendingsCollection = array();
		public $mutex;
		private $__isDaemon = false;
		
		public function __construct() {
			$this->__setRunningMode();
			$this->mutex = sem_get(ftok(__FILE__, 'm'));
			$this->Logger = new Logger(LOGFILE);
			$this->Output = new Output();
			$this->Sending = Factory::getInstance('Model/Sending');
		}
		
		
		public function init() {
			$this->resetInitialPool();
			$this->__run();
		}
		
		private function __run() {

			while(true) {
				if($this->countToSend()) {
					
					$next = $this->getNextToSend();
					
					if(isset($next['id']) && $next['id']) {
						try {
							$nextActivity = new SendingActivity($next['id']);
							$nextActivity->mutex = $this->mutex;
							$nextActivity->__isDaemon = $this->__isDaemon;
							$this->SendingsCollection[] = $nextActivity;
							$this->log('Starting sending '.$next['id'], 'info');
							$nextActivity->start();
							$nextActivity->wait();
							
							Factory::getInstance('Model/Sending')->setParamsInSendings($next['id']);
							
							$this->pushToPool($next['id']);
							$nextActivity->notify();
							
							unset($next);
						}
						catch(Exception $e) {
							$this->log('Error starting sending '.$next['id'], 'error');
						}
					}
					
				}
				else {
					//un po di garbage collector
				}
			}
		}
		
		private function __setRunningMode() {
			$args = getopt('i');
			if(!isset($args['i'])) {
				Daemon::daemonize();
				$this->__isDaemon = true;
			}
		}
		
		public function log($msg, $type) {
			$this->Logger->$type(date('d/m/Y H:i:s').' '.$msg);
			if(!$this->__isDaemon) {
				$this->Output->$type(date('d/m/Y H:i:s').' '.$msg);
			}
		}
		
		public function pushToPool($id) {
			if(sem_acquire($this->mutex)) {
				$fp = fopen(SENDING_POOL, 'a');
				fwrite($fp, $id."\n");
				fclose($fp);
   			 	sem_release($this->mutex);
			}
		}
		
		
		public function resetInitialPool() {
			if(sem_acquire($this->mutex)) {
				$oldPool = file(SENDING_POOL, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
				if(is_array($oldPool)) {
					$newPool = array();
					foreach($oldPool as $entityId) {
						$entity = (new Sending())->load($entityId);
						$entity->data['status'] = Sending::$WAITING;
						try {
							$entity->save();
							unset($entity);
						}
						catch(Excepion $e) {
							$newPool[] = $entityId;
						}
					}
					
					file_put_contents(SENDING_POOL, implode("\n", $newPool));
					
	   			 	sem_release($this->mutex);
	   			}
			}
		}
		
		public function countToSend() {
			return $this->Sending->countToSend();
		}
		
		public function getNextToSend() {
			return $this->Sending->getNextToSend();	
		}
		
	}

?>
