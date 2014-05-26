<?php

	require_once 'PHPMailer'.DS.'class.phpmailer.php';
	
	class Mailer extends PHPMailer {
	
		public function __construct($exceptions = false) {
			$this->CharSet = 'UTF-8';
   			parent::__construct($exceptions);
  		}
	
		public function resetAddresses() {
			$this->to = array();
			$this->cc = array();
			$this->bcc = array();
			$this->ReplyTo = array();
			$this->all_recipients = array();
			$this->Body = '';
			$this->AltBody = '';
		}
		
	}


?>
