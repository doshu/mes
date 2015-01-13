<?php
	
	App::uses('CakeResponse', 'Network');

	class ApiResponse extends CakeResponse {
		
		protected $_body = array();
		protected $_controller = null;
		
		public function __construct($controller) {
			parent::__construct();
			$this->_controller = $controller;
		}
		
		public function addError($code, $details = array(), $text = '') {
			$this->_body['errors'][] = array(
				'code' => $code,
				'details' => $details,
				'text' => $text
			);
		}
		
		public function setData($data) {
			$this->_body['data'] = $data;
		}
		
		public function send() {
		
			$this->_controller->set('response', $this->getResponse());
			$this->_controller->set('_serialize', array('response'));
			$response = $this->_controller->render();
		
			if (isset($this->_headers['Location']) && $this->_status === 200) {
				$this->statusCode(302);
			}

			$codeMessage = $this->_statusCodes[$this->_status];
			$this->_setCookies();
			$this->_sendHeader("{$this->_protocol} {$this->_status} {$codeMessage}");
			$this->_setContent();
			$this->_setContentLength();
			$this->_setContentType();
			foreach ($this->_headers as $header => $values) {
				foreach ((array)$values as $value) {
					$this->_sendHeader($header, $value);
				}
			}
			
			$this->_sendContent($this->_body);
			
			exit;
		}
		
		public function getResponse() {
			return $this->_body;
		}
		 
	}
?>
