<?php

App::uses('Controller', 'Controller');


class AppController extends Controller {


	public $components = array(
		'Auth' => array(
			'authenticate' => array(
		        'Form' => array(
		            'passwordHasher' => 'Blowfish',
		            'scope' => array('User.active' => 1)
		        )
		    )
		),
        'Session', 
        'RequestHandler', 
        'Security', 
        'Xuser'
    );
    
	public $helpers = array('Session', 'Html', 'Form', 'Phpjs', 'SafeDate', 'Javascript');
	
	public $autoPaginate = false;
	public $autoPaginateOp = array();
	public $paginate;

	public function beforeFilter() {
	
		$this->Auth->loginAction = array('plugin' => false, 'controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('plugin' => false, 'controller' => 'mails', 'action' => 'dashboard');
		$this->Auth->unauthorizedRedirect = array('plugin' => false, 'controller' => 'mails', 'action' => 'dashboard');
		$this->Auth->loginError = __('Errore! Username o password errati.');
		
		if(!$this->Auth->loggedIn()) {
			$this->Auth->authError = false;
		}
		
		$this->Auth->authorize = array('Controller');
		
		$this->Security->csrfExpires = '+1 hour';
		$this->Security->csrfUseOnce = false;
		$this->Security->blackHoleCallback = 'blackhole';
		
		$this->request->addDetector(
			'json', 
			array('callback' => function ($request) {
					return isset($request->params['ext']) && $request->params['ext'] == 'json';
				}
			)
		);
		
		
		if(!$this->Session->check('mes_cookie_enabled') && !$this->isCheckCookieAction()) {
			setcookie('mes_cookie_check', 1, 0, '/', $_SERVER['HTTP_HOST'], false, true);
			$this->redirect(Configure::read('check_cookie_action'));
		}
		
		
		//$this->Auth->allow();
	}

	
	private function isCheckCookieAction() {
		$action = Configure::read('check_cookie_action');
		return $action['plugin'] == $this->request->params['plugin'] &&
				$action['controller'] == $this->request->params['controller'] &&
				$action['action'] == $this->request->params['action'];
	}
	
	
	public function implementedEvents() {
		$events = parent::implementedEvents();
		$initializeEvent = array();
		$initializeEvent[] = array('callable' => $events['Controller.initialize']);
		$initializeEvent[] = array('callable' => '_securitySettings');
        $events['Controller.initialize'] = $initializeEvent;
        
        return $events;
    }
	
	public function _securitySettings($event) {
		$action = $this->request->params['action'];
		$sec = '__securitySettings_'.$action;
		if(method_exists($this, $sec))
			$this->$sec();
		
		return $event;
	}  
	
	public function blackHole($type) {
		if(Configure::read('debug')) {
			debug($type); exit;
		}
		else {
    		throw new ForbiddenException();
    	}
	}
	
	public function isAuthorized() {
		return true;
	}
	
	public function paginate($object = NULL, $scope = array(), $whitelist = array()) {
		$key = md5($this->params['plugin'].$this->params['controller'].$this->params['action']);
		if(isset($this->request->params['named']['limit']) && !empty($this->request->params['named']['limit'])) {
			$this->Session->write($key.'.limit', $this->request->params['named']['limit']);
		}
		else {
			if($this->Session->check($key.'.limit')) {
				$this->paginate[$object]['limit'] = $this->Session->read($key.'.limit');
				$this->request->params['named']['limit'] = $this->paginate[$object]['limit'];
			}
		}
		if($this->autoPaginate === true) {
			if($this->request->isPost()) {
				if(isset($this->request->data[$object])) {
					$cond[$object] = $this->request->data[$object];
					$cond = $this->searchToConditions(
						$cond,
						$this->autoPaginateOp
					);
					
					$this->Session->write($key.'.conditions', $cond);
				}
			}
			if($this->Session->check($key.'.conditions')) {
				$userScope = $this->Session->read($key.'.conditions');
				$scope = array_merge($scope, $userScope['conditions']);
				
				if(!empty($userScope['having'])) {
					$this->paginate[$object]['having'] = $userScope['having'];
				}
			}
		}
		return parent::paginate($object, $scope, $whitelist);
	}
	
	public function filter_reset($key = null) {
		if(!is_null($key) && $this->Session->check($key))
			$this->Session->delete($key);
		$this->redirect($this->referer('/', true));
	}
	
	private function searchToConditions($search, $info) {
		$conditions = array();
		$having = array();
		foreach($search as $model => $fields) {
			foreach($fields as $field => $value) {
				if($value != '') {
					if(isset($info[$field])) {
						$info[$field] = (array)$info[$field];
						$operator = strtoupper($info[$field][0]);
						$options = isset($info[$field][1])?$info[$field][1]:array();
						
						if(isset($options['having']) && $options['having'] = true)
							$container = &$having;
						else
							$container = &$conditions;
						
						switch($operator) {
							case 'LIKE':
								$container[$model.'.'.$field.' LIKE'] = '%'.$value.'%';
							break;
							case 'BETWEEN':
								if(isset($value['from']) && isset($value['to'])) {
									$value = array($value['from'], $value['to']);
								}
								else {
									$value = array_values($value);
								}
								
								if(isset($options['type']) && ($options['type'] == 'date' || $options['type'] == 'datetime')) {
									$outputFormat = (
											$options['type'] == 'date' && 
											(!isset($options['time']) || !$options['time'])
										)?'Y-m-d':'Y-m-d H:i:s';
									$format = (isset($options['format']) && !empty($options['format']))?$options['format']:'d/m/Y';
									$toConvert = (isset($options['convert']) && $options['convert']);
									if(isset($value[0])) {
										$tmpDate = DateTime::createFromFormat(
											$format, 
											$value[0], 
											new DateTimeZone($toConvert?AuthComponent::user('timezone'):date_default_timezone_get())
										);
										if($tmpDate instanceof DateTime) {
											if(isset($options['time']) && $options['time']) {
												$tmpDate->setTime(0, 0, 0);
											}
											$tmpDate->setTimezone(new DateTimeZone('UTC'));
											$value[0] = $tmpDate->format($outputFormat);
										}
									} 	
									if(isset($value[1])) {
										$tmpDate = DateTime::createFromFormat(
											$format, 
											$value[1], 
											new DateTimeZone($toConvert?AuthComponent::user('timezone'):date_default_timezone_get())
										);
										
										if($tmpDate instanceof DateTime) {
											if(isset($options['time']) && $options['time']) {
												$tmpDate->setTime(23, 59, 59);
											}
											$tmpDate->setTimezone(new DateTimeZone('UTC'));
											
											$value[1] = $tmpDate->format($outputFormat);
										}
									} 
								}
								if(isset($value[0]) && $value[0] !== '') {
									$container[$model.'.'.$field.' >='] = $value[0];
								}
								if(isset($value[1]) && $value[1] !== '') {
									$container[$model.'.'.$field.' <='] = $value[1];
								}
								
							break;
							default:
								if(is_array($value)) {
									$value = array_filter(array_values($value), function($el) { return $el !== ''; });
									if(!empty($value)) {
										if(strtoupper($info[$field]) != '=') {
											$container[$model.'.'.$field.' '.$operator] = $value;
										}
										else {
											$container[$model.'.'.$field] = $value;
										}
									}
								}
								else {
									if(strtoupper($operator) != '=') {
										$container[$model.'.'.$field.' '.$operator] = $value;
									}
									else {
										$container[$model.'.'.$field] = $value;
									}
								}
							break;	
						}
					}
				}
			}
		}
		return array('conditions' => $conditions, 'having' => $having);
	}
	
	/*
	public function admin_filter_reset($plugin, $controller, $action) {
		$plugin = $plugin == "null"?null:$plugin;
		$this->Session->delete(md5($plugin.$controller.$action));
		exit;
	}
	*/
	
	
	public function afterFilter() {
		if($this->request->is('json'))
			$this->response->type('text/html');
	}
}
