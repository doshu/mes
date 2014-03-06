<?php
App::uses('AppController', 'Controller');


class SmtpsController extends AppController {


	public function index() {
		$this->Smtp->recursive = -1;
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'email' => 'LIKE',
			'host' => 'LIKE',
			'name' => 'LIKE',
			'port' => 'LIKE',
			'username' => 'LIKE'
		);
		$this->paginate = array('Smtp' => array('order' => array('Smtp.created' =>  'desc')));
		
		$this->set(
			'smtps',
			$this->paginate('Smtp', array('Smtp.user_id' => $this->Auth->user('id')))
		);
		
		$this->set(
			'count', 
			$this->Smtp->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('Smtp.user_id' => $this->Auth->user('id'))
				)
			)
		);
	}


	public function view($id = null) {
		
		$this->set('smtp', $this->Smtp->find('first', array('recursive' => -1, 'conditions' => array('Smtp.id' => $id))));
		$this->set(
			'count', 
			$this->Smtp->Sending->find(
				'count', 
				array(
					'recursive' => -1,
					'conditions' => array(
						'smtp_id' => $id
					)
				)
			)
		);
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Smtp->create();
			if ($this->Smtp->save($this->request->data)) {
				$this->Session->setFlash(__("L'Indirizzo di invio è stato salvato"), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
	}
	
	
	public function edit($id = null) {
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Smtp->save($this->request->data)) {
				$this->Session->setFlash(__("L'Indirizzo di invio è stato salvato"), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		} else {
			$this->request->data = $this->Smtp->find('first', array('conditions' => array('Smtp.id' => $id)));
		}
	}


	public function delete($id = null) {
		$this->Smtp->id = $id;
		
		if ($this->Smtp->delete()) {
			$this->Session->setFlash(__("L'Indirizzo di invio è stato eliminato"), 'default', array(), 'error');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		$this->redirect(array('action' => 'index'));
	}
	
	
	
	public function import($mailinglist = null) {
		
		if($this->request->isPost()) {
			App::uses('Member', 'Model');		
			$csvType = $mailinglist == null?Member::EMAILANDLIST:Member::ONLYEMAIL;
			$importExists = (isset($this->request->data['Member']['importExists']) && $this->request->data['Member']['importExists']); 
			$error = $this->Member->validateImportFile($this->request->data['Member']['file'], $csvType, $importExists);
			
			if(empty($error)) {
				$import = $this->Member->importFile(
					$this->request->data['Member']['file']['tmp_name'], 
					$csvType,
					$importExists, 
					$mailinglist
				);
				
				if($import['status']) {
					if($mailinglist)
						$this->redirect(array('controller' => 'mailinglists', 'action' => 'view', $mailinglist));
					else 
						$this->redirect(array('controller' => 'members', 'action' => 'index'));
				}
				else {
					$this->set('errors', $import['errors']);
					$this->Session->setFlash(__("Errore durante l'importazione"), 'default', array(), 'error');
				}
			}
			else {
				$this->set('errors', $error);
				$this->Session->setFlash(__("Errore durante l'importazione"), 'default', array(), 'error');
			}
			
		}
		
		if($mailinglist != null) {
			$this->set(
				'mailinglist', 
				$this->Member->Mailinglist->find(
					'first',
					array(
						'recursive' => -1,
						'conditions' => array('id' => $mailinglist)
					)
				)
			);
		}
	}
	
	/*
	 * $hasM è 0 se si sta importando direttamente da members, altrimenti 1 se si sta importando da una mailinglist
	 */
	public function example($hasM) {
		//debug($this->Member->getImportExample($hasM)); exit;
		$this->response->type('csv');
		$this->response->body($this->Member->getImportExample($hasM));
		$filename = !$hasM?__('esempio_importazione_email_con_liste'):__('esempio_importazione_email');
		$this->response->download($filename.'.csv');
		return $this->response;
	}
	
	
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Smtp, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_mailinglist() {
		$this->Xuser->checkPerm($this->Smtp->User->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Smtp, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Smtp, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
