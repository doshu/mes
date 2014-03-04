<?php

	App::uses('ExceptionRenderer', 'Error');

	class ProductionExceptionRenderer extends ExceptionRenderer {
	
		private $__redirectProduction = null;
		private $__ajax = array(
			'ajax',
			'json'
		);
		
		
		public function __construct(Exception $exception) {
			
			parent::__construct($exception);
			$this->__redirectProduction = Configure::check('Exception.redirectProduction')?
				Configure::read('Exception.redirectProduction'):'/';
			$this->__errorRedirectProduction = Configure::check('Exception.errorRedirectProduction')?
				Configure::read('Exception.errorRedirectProduction'):'/pages/error500';
			
		}
		
		private function isAjax() {
			foreach($this->__ajax as $ajax) {
				if($this->controller->request->is($ajax)) {
					return true;
				}
			}
			return false;
		}
		
		public function notFound($error) {
			$this->controller->response->statusCode($error->getCode());
			if($this->isAjax()) {
				$this->controller->response->send();
				exit;
			}
			else {
				$this->controller->redirect($this->__redirectProduction);
			}
		}
		
		public function forbidden($error) {
			$this->controller->response->statusCode($error->getCode());
			if($this->isAjax()) {
				$this->controller->response->send();
				exit;
			}
			else {
				$this->controller->redirect($this->__redirectProduction);
			}
			
		}
		
		
		public function error500($error) {
			if(Configure::read('debug')) {
				parent::error500($error);
			}
			else {
				$this->controller->response->statusCode($error->getCode());
				if($this->isAjax()) {
					$this->controller->response->send();
					exit;
				}
				else {
					
					$this->controller->redirect($this->__errorRedirectProduction);
				}
			}
		}
		
		
		public function error400($error) {
			$this->controller->response->statusCode($error->getCode());
			if($this->isAjax()) {
				$this->controller->response->send();
				exit;
			}
			else {
				$this->controller->redirect($this->__redirectProduction);
			}
		}
		
		
		
	}

?>
