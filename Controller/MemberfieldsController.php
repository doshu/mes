<?php
App::uses('AppController', 'Controller');


class MemberfieldsController extends AppController {


	public function index() {
		
		$this->Memberfield->recursive = -1;
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'name' => 'LIKE',
			'code' => 'LIKE'
		);
		
		$this->paginate = array('Memberfield' => array('order' => array('Memberfield.created' =>  'desc')));
		
		$this->set(
			'memberfields',
			$this->paginate('Memberfield', array('Memberfield.user_id' => $this->Auth->user('id')))
		);
		
		$this->set(
			'count', 
			$this->Memberfield->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('Memberfield.user_id' => $this->Auth->user('id'))
				)
			)
		);
	}
	
	
	public function add() {
		if ($this->request->is('post')) {
			$this->Memberfield->create();
			$this->request->data['Memberfield']['user_id'] = $this->Auth->user('id');
			if (
				$this->Memberfield->save($this->request->data, true, array(
					'Memberfield' => array('name', 'code', 'type', 'in_grid', 'user_id')
				))
			) {
				$this->Session->setFlash(__('Il Campo Membro è stato salvato'), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
	}
	
	
	public function edit($id) {
		
		if($this->request->is('post') || $this->request->is('put')) {
			if($this->Memberfield->save($this->request->data)) {
				$this->Session->setFlash(__('Il campo membro è stato salvato'), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			}
			else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
		else {
			$this->request->data = $this->Memberfield->find('first', array('conditions' => array('Memberfield.id' => $id)));
		}
	}
	
	public function delete($id) {
		$this->Memberfield->id = $id;

		if ($this->Memberfield->delete()) {
			$this->Session->setFlash(__('Il Campo Membro è stato eliminato'), 'default', array(), 'info');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		$this->redirect(array('action' => 'index'));
	}
	
	
	public function view($id) {
		$this->set('memberfield', $this->Memberfield->find('first', array('conditions' => array('Memberfield.id' => $id))));
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Memberfield, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Memberfield, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Memberfield, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
