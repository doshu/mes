<?php
/**
 * Smtp transport protocol with testo connection function
 *
 */

App::uses('SmtpTransport', 'Network/Email');


class CustomSmtpTransport extends SmtpTransport {

	public function testConnection() {
		
		try {
			$this->_connect();
			$this->_auth();
			$this->_disconnect();
			return true;
		}
		catch(Exception $e) {
			debug($e->getMessage());
			return false;
		}
	}
}
