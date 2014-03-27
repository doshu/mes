<?php
App::uses('AppController', 'Controller');


class TemplatesController extends AppController {


	public function index() {
	
		$this->Template->recursive = 0;
		
		$count = $this->Template->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('user_id' => $this->Auth->user('id'))
			)
		);
		
		$this->set('count', $count);
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'name' => 'LIKE',
			'description' => 'LIKE',
			'created' => array('BETWEEN', array('convert' => true, 'type' => 'date', 'time' => true))
		);
		
		$this->paginate = array('Template' => array('order' => array('Template.created' =>  'desc')));
		$this->set('templates', $this->paginate('Template', array('Template.user_id' => $this->Auth->user('id'))));
		
	}


	public function view($id = null) {

		$this->Template->recursive = -1;
		$template = $this->Template->read(null, $id);
	
		$this->set('template', $template);
	}


	public function add() {
		
		if ($this->request->is('post')) {
			$this->Template->create();
			if($this->Template->save(
				$this->request->data, 
				array(
					'fieldList' => array(
						'Template' => array('name', 'description', 'html', 'text', 'created', 'modified', 'user_id'),
					)
				)
			)) {
				$this->Session->setFlash(__("Il Template è stato salvato"), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
		$this->set('member_custom_fields', $this->Template->User->Member->getModelFields());
	}


	public function edit($id = null) {
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if($this->Template->save(
				$this->request->data, 
				array(
					'fieldList' => array(
						'Template' => array('name', 'description', 'html', 'text', 'created', 'modified', 'user_id'),
					)
				)
			)) {
				$this->Session->setFlash(__("Il Template è stato salvato"), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		} 
		else {
			$this->request->data = $this->Template->find('first', array('conditions' => array('Template.id' => $id)));
		}
		
		$this->set('member_custom_fields', $this->Template->User->Member->getModelFields());
	}

	public function delete($id = null) {
	
		$this->Template->id = $id;
		
		if ($this->Template->delete()) {
			$this->Session->setFlash(__("Il Template è stato eliminato"), 'default', array(), 'info');
			$this->redirect(array('action' => 'index'));
		}
		else
			$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		
		
		$this->redirect($this->referer(array('action' => 'view', $this->Template->id), true));
	}
	
	
	public function bulk() {
	
		$result = false;
		$message = __('Errore durante l\'operazione.');
		if(!empty($this->request->data['Template']['selected'])) {
			switch($this->request->data['Template']['action']) {
				case 'bulkDelete':
					list($result, $message) = $this->Template->bulkDelete($this->request->data['Template']['selected']);
				break;
				default:
					$this->Session->setFlash(__("Seleziona una operazione valida"), 'default', array(), 'error');
					$this->redirect($this->referer(true, '/'));
			}
		}
		else {
			$this->Session->setFlash(__("Seleziona almeno un indirizzo"), 'default', array(), 'error');
			$this->redirect($this->referer(true, '/'));
		}
		
		if($result) {
			if($message === true) {
				$message = __('Operazione eseguita con successo.');
			}
			$this->Session->setFlash($message, 'default', array(), 'info');
		}
		else {
			$this->Session->setFlash($message, 'default', array(), 'error');
		}
		$this->redirect($this->referer(true, '/'));
	}
	
		
	public function preview($id) {
		$this->layout = 'preview';
		$this->Template->recursive = -1;
		$this->set('template', $this->Template->read(null, $id));
	}
	
	
	protected function __securitySettings_bulk() {
		$this->request->onlyAllow('post');
		
		if(!isset($this->request->data['Template']['action']))
			$this->request->data['Template']['action'] = null;
		if(!isset($this->request->data['Template']['selected']))
			$this->request->data['Template']['selected'] = array();
			
		switch($this->request->data['Template']['action']) {
			case 'bulkDelete':
				$this->Security->allowedControllers = array('templates');
				$this->Security->allowedActions = array('index');
				$this->request->data['Template']['selected'] = explode(',', $this->request->data['Template']['selected']);
				foreach($this->request->data['Template']['selected'] as $selected) {
					$this->Xuser->checkPerm($this->Template, $selected);
				}
			break;
		}
	}
	
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Template, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Template, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_preview() {
		$this->Xuser->checkPerm($this->Template, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->Xuser->checkPerm($this->Template, isset($this->request->pass[0])?$this->request->pass[0]:null);
		$this->request->onlyAllow('post', 'delete');
	}
}
