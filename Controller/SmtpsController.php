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
	
	
	
	public function bulk() {
	
		$result = false;
		$message = __('Errore durante l\'operazione.');
		
		switch($this->request->data['Smtp']['action']) {
			case 'bulkDelete':
				list($result, $message) = $this->Smtp->bulkDelete($this->request->data['Smtp']['selected']);
			break;
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
	
	
	public function test($id) {
		
		$this->set('_serialize', array('result'));
		$smtp = $this->Smtp->read(null, $id);
		App::import('Vendor', 'Mailer');
		$mailer = new Mailer(true);
		$mailer->IsSMTP();
		$mailer->Host = $smtp['Smtp']['host'];
		$mailer->SMTPAuth = true;
		$mailer->Username = $smtp['Smtp']['username'];
		$mailer->Password = $smtp['Smtp']['password'];
		
		$mailer->AuthType = $smtp['Smtp']['authtype'];
		
		if($smtp['Smtp']['enctype'] == 'tls' || $smtp['Smtp']['enctype'] == 'ssl') {
			$mailer->SMTPSecure = $smtp['Smtp']['enctype'];   
		}
		
		$vendorPath = App::path('Vendor');
		$mailer->PluginDir = $vendorPath[0].'PHPMailer'.DS;
		try {
			if($mailer->SmtpTest()) {
				$this->Session->setFlash(__("L'indirizzo di invio è configurato correttamente"), 'default', array(), 'info');
			}
			else {
				$this->Session->setFlash(__("Impossibile collegarsi con l'indirizzo di invio"), 'default', array(), 'error');
			}
		}
		catch(phpmailerException $e) {
			$this->Session->setFlash(__("Impossibile collegarsi con l'indirizzo di invio (".$e->getMessage().")"), 'default', array(), 'error');
		}
		$this->redirect($this->referer('/', true));
	}
	
	protected function __securitySettings_bulk() {
		$this->request->onlyAllow('post');
		if(isset($this->request->data['Smtp']['action']) && isset($this->request->data['Smtp']['selected'])) {
			switch($this->request->data['Smtp']['action']) {
				case 'bulkDelete':
					$this->Security->allowedControllers = array('smtps');
					$this->Security->allowedActions = array('index');
					$this->request->data['Smtp']['selected'] = explode(',', $this->request->data['Smtp']['selected']);
					foreach($this->request->data['Smtp']['selected'] as $selected) {
						$this->Xuser->checkPerm($this->Smtp, $selected);
					}
				break;
				default:
					$this->Security->blackHole($this, 'auth');
			}
		}
		else {
			$this->Security->blackHole($this, 'auth');
		}
	}
	
	protected function __securitySettings_test() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Smtp, isset($this->request->pass[0])?$this->request->pass[0]:null);
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
