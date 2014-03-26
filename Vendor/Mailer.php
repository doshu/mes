<?php

	App::import('Vendor', 'PHPMailer', array('file' => 'PHPMailer/class.phpmailer.php'));
	
	
	class Mailer extends PHPMailer {
	
		public function resetAddresses() {
			$this->to = array();
			$this->cc = array();
			$this->bcc = array();
			$this->ReplyTo = array();
			$this->all_recipients = array();
		}
		
		
		public function smtpTest() {
			return $this->SmtpConnect();
		}
		
		
		public function SmtpConnect($options = array()) {
			App::import('Vendor', 'SmtpMailer', array('file' => 'SmtpMailer.php'));
			$this->smtp = new SmtpMailer();
			return parent::SmtpConnect($options);
		}
		
		/**
		 * Return 0 if not exists, 1 if exists and 2 if not able to detect
		 *
		 */
		public function smtpCheckAddress($address) {
			App::import('Vendor', 'SmtpMailer', array('file' => 'SmtpMailer.php'));
			try {
				$smtp = $this->getAddressMX($address);
				
				if($smtp) {
					$this->smtp = new SmtpMailer();
					if($this->smtp->checkAddress($address, $smtp, 'verify@mes.com')) {
						return 1; //exists
					}
					else {
						return 0; //not exists
					}
				}
				else {
					return 0;
				}
			}
			catch(Exception $e) {
				return 2; //not able to detect
			}
			
		}
		
		public function getAddressMX($address) {
			$address = explode('@', $address);
			if(!isset($address[1]) || empty($address[1]))
				throw new Exception('Invalid Email Address');
			if(getmxrr($address[1], $mx, $w)) {
				arsort($w);
				$first = each($w);
				return $mx[$first[0]];
			}
			return false;
		}
		
	}


	

?>
