<?php

	App::uses('Component', 'Controller');
	
	class XuserComponent extends Component {
	
		private $__controller;
		private $__model = null;
		private $__pk = null;
		private $__userId = null;
		private $__params = array();
		private $__toCheck = array();
		public $checkMethod = 'checkPerm';
		
		public function initialize(Controller $controller) {
			$this->__controller = $controller;
		}
		
		public function checkPerm($model, $pk, $params = array(), $userId = null) {

			if(is_null($userId)) {
				$userId = $this->__controller->Auth->user('id');
			}

			$this->__toCheck[] = array(
				'model' => $model,
				'pk' => $pk,
				'params' => $params,
				'userId' => $userId
			);
		}
		
		public function startup(Controller $controller) {
			foreach($this->__toCheck as $toCheck) {
				try {
					$checkMethod = new ReflectionMethod($toCheck['model'], $this->checkMethod);
					if(!$checkMethod->invoke($toCheck['model'], $toCheck['pk'], $toCheck['params'], $toCheck['userId'])) {
						
						throw new ForbiddenException();
					}
				}
				catch(ReflectionException $e) {
					throw new ForbiddenException();
				}
			}
			return true;
		}
	} 


?>
