<?php

	require_once 'PHPMailer'.DS.'class.phpmailer.php';
	
	class Mailer extends PHPMailer {
	
		public function resetAddresses() {
			$this->to = array();
			$this->cc = array();
			$this->bcc = array();
			$this->ReplyTo = array();
			$this->all_recipients = array();
		}
		
	}


?>
