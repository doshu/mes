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
			App::import('Vendor', '_SMTP', array('file' => 'PHPMailer/class.smtp.php'));
			return $this->SmtpConnect();
		}
		
	}


	

?>
