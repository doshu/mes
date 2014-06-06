<?php
App::uses('AppController', 'Controller');


class ApisController extends AppController {


	public function index() {
	
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'name' => 'LIKE',
			'description' => 'LIKE',
			'created' => array('BETWEEN', array('convert' => true, 'type' => 'date', 'time' => true))
		);
		
		$this->paginate = array('Api' => array('recursive' => -1, 'order' => array('Api.created' =>  'desc')));
		$keys = $this->paginate('Api', array('user_id' => $this->Auth->user('id')));
		$this->set('keys', $keys);
	}
	
	public function add() {
		
		if($this->request->is('post')) {
			$this->request->data['Api']['acl'] = $this->Api->sanitizeAcl($this->request->data['Api']['acl'], $this->Auth->user('id'));
			$this->request->data['Api']['acl'] = json_encode($this->request->data['Api']['acl']);
			$this->request->data['Api']['salt'] = uniqid('', true);
			$this->request->data['Api']['user_id'] = $this->Auth->user('id');
			//lock table
			$this->Api->getDataSource()->lockTable(array('apis', 'apis' => 'Api'), 'WRITE');
			$this->request->data['Api']['clientkey'] = $this->Api->createClientKey();
			$this->request->data['Api']['enckey'] = $this->Api->createEncKey();
			try {
				$this->Api->create();
				if($this->Api->save($this->request->data)) {
					$this->Session->setFlash(__("La chiave API è stata salvata"), 'default', array(), 'info');
					$this->redirect(array('action' => 'index'));
				}
				else {
					$this->Session->setFlash(__("Errore durante il salvataggio"), 'default', array(), 'error');
				}
				$this->Api->getDataSource()->unlockTables();
			}
			catch(Exception $e) {
				$this->Api->getDataSource()->unlockTables();
				throw $e;
			}
		}
		$this->set('lists', $this->Api->User->Mailinglist->find('list', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id')
			)
		)));
	}
	
	
	public function edit($id) {

		if($this->request->is(array('post', 'put'))) {
			$this->request->data['Api']['acl'] = $this->Api->sanitizeAcl($this->request->data['Api']['acl'], $this->Auth->user('id'));
			$this->request->data['Api']['acl'] = json_encode($this->request->data['Api']['acl']);
			$this->request->data['Api']['user_id'] = $this->Auth->user('id');
			if(
				$this->Api->save($this->request->data, array(
					'fieldList' => array('Api' => array('acl', 'name', 'description'))
				))
			) {
				$this->Session->setFlash(__("La chiave API è stata salvata"), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			}
			else {
				
				$this->Session->setFlash(__("Errore durante il salvataggio"), 'default', array(), 'error');
			}
		}
		else {
			$this->request->data = $this->Api->read(null, $id); 
			$this->request->data['Api']['acl'] = json_decode($this->request->data['Api']['acl'], true);
		}
		
		$this->set('lists', $this->Api->User->Mailinglist->find('list', array(
			'conditions' => array(
				'user_id' => $this->Auth->user('id')
			)
		)));
	}
	
	
	public function view($id) {
		$this->set('key', $this->Api->find('first', array('recursive' => -1, 'conditions' => array('id' => $id))));
	}
	
	
	public function delete($id) {
		
		$this->Api->id = $id;
		if($this->Api->delete()) {
			$this->Session->setFlash(__("La chiave API è stata eliminata"), 'default', array(), 'info');
		}
		else {
			$this->Session->setFlash(__("Impossibile eliminare la chiave API. Riprovare."), 'default', array(), 'error');
		}
		
		$this->redirect(array('action' => 'index'));
	}
	
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Api, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Api, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	
	protected function __securitySettings_delete() {
		$this->Xuser->checkPerm($this->Api, isset($this->request->pass[0])?$this->request->pass[0]:null);
		$this->request->onlyAllow('post', 'delete');
	}
}
