<?php

	class Spamassassin {
	
		private $pointBin = 'spamc -c';
		private $detailsBin = 'spamc -y';
		
		public function getSpamPoint($text) {
			if(!empty($text)) {
				$cmd = 'echo '.escapeshellarg("\n\n".$text).' | '.($this->pointBin); //added \n\n as end of headers and start of body
				$result = trim(shell_exec($cmd));

				if($result != null && !empty($result)) {
					$toParse = $result;
				}
				else {
					throw new InternalErrorException(__('Errore durante il controllo Antispam'));
				}
			}
			else {
				$toParse = '0/0';
			}
			
			$parsed = $this->__parseResult($toParse);
			if(is_array($parsed)) {
				return $parsed;
			}
			else {
				throw new InternalErrorException(__('Errore durante il controllo Antispam'));
			}
		}
		
		
		public function getSpamDetails($text) {
			if(!empty($text)) {
				$cmd = 'echo '.escapeshellarg("\n\n".$text).' | '.($this->detailsBin); //added \n\n as end of headers and start of body
				$result = trim(shell_exec($cmd));
				if($result != null && !empty($result)) {
					return $result;
				}
				else {
					throw new InternalErrorException(__('Errore durante il controllo Antispam'));
				}
			}
			else {
				return '';
			}
		}
		
		
		private function __parseResult($result) {
			$parts = explode('/', $result);
			if(count($parts) == 2) {
				$parts[0] = trim($parts[0]);
				$parts[1] = trim($parts[1]);
				if(is_numeric($parts[0]) && is_numeric($parts[1])) {
					
					return array('point' => $parts[0], 'limit' => $parts[1]);
				}
			}
			return false;
		}
	}

?>
