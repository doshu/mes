<?php

	App::import('Vendor', '_SMTP', array('file' => 'PHPMailer/class.smtp.php'));
	
	class SmtpMailer extends _SMTP {
	
		public function checkAddress($address, $host, $from) {
			if($this->Connect($host, 25) && $this->Hello($host)) {
				$this->client_send('MAIL FROM:<'.$from.'>'.$this->CRLF);
				$rply = $this->get_lines();
    			$code = substr($rply, 0, 3);
    			if($code == 250) {
    				$this->client_send('RCPT TO:<' . $address . '>' . $this->CRLF);
					$rply = $this->get_lines();
   					$code = substr($rply, 0, 3);
   					$this->Close();
   					if($code == 250) {
   						return true;
   					}
   					elseif($code == 550) {
   						return false;
   					}
   					else {
   						throw new Exception('Not Able to Detect');
   					}
    			}
    			else {
    				throw new Exception('Cannot Send Email');
    			}
			}
			else {
				throw new Exception('Cannot connect to Smtp Server');
			}
		}
	}

?>
