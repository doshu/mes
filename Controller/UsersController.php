<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {


	public function __index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}


	public function __add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}


	public function __edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
	}


	public function __delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function login() {
	
		$this->response->disableCache();
		if($this->request->isPost()) {
			
			if($this->Auth->login()) {
				
				return $this->redirect($this->Auth->loginRedirect);
			}
			else
				$this->Session->setFlash(__('Username o Password non validi.'), 'default', array(), 'auth');
		}
		elseif($this->Auth->loggedIn())
			return $this->redirect($this->Auth->loginRedirect);
	}
	
	
	
	public function logout() {
	
		$this->response->disableCache();
		$this->Auth->logout();
		return $this->redirect($this->Auth->loginAction);
	}
	
	public function settings() {
		if($this->request->isPost()) {
			$this->User->validator()
				->add('oldpwd', array(
					'required' => array(
						'rule' => 'notEmpty',
						'message' => __('Questo è un campo obbligatorio')
					),
					'correctPassword' => array(
						'rule' => array('isUserCorrectPassword', $this->Auth->user('id')),
						'message' => __('La password inserita è errata')
					)
				))
				->add('newpwd', 'required', array(
					'rule' => 'notEmpty',
					'message' => __('Questo è un campo obbligatorio')
				))
				->add('newpwd2', array(
					'required' => array(
						'rule' => 'notEmpty',
						'message' => __('Questo è un campo obbligatorio')
					),
					'repeatPassword' => array(
						'rule' => 'repeatPasswordValidation',
						'message' => __('Le password devono coincidere')
					),
					
				));
			
			$this->User->set($this->request->data);
			if($this->User->validates()) {
				$this->User->read(null, $this->Auth->user('id'));
				$this->User->set('password',$this->request->data['User']['newpwd']);
				try {
					$this->User->save();
					$this->redirect(array('action' => 'logout'));
				}
				catch(Exception $e) {
					$this->Session->setFlash(__('Errore durante il salvataggio'), 'default', array(), 'error');
				}
			}
			else
				$this->request->data['User'] = array();
		}
	}
	
	
	public function checkCookie() {
		
		if(isset($_COOKIE['mes_cookie_check'])) {
			$this->Session->write('mes_cookie_enabled', 1);
			$this->redirect($this->referer('/', true));
		}
		
	}


	protected function __securitySettings_login() {
		Configure::write('no_check_cookie', true);
		$this->Auth->allow('login');
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}
	
	protected function __securitySettings_checkCookie() {
		$this->Auth->allow('checkCookie');
		if($this->request->isPost()) {
			Configure::write('no_check_cookie', true);
		}
	}
		
	
	
}
