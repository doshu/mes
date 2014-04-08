<?php
App::uses('AppController', 'Controller');


class SendingsController extends AppController {


	public function view($id = null) {
	
		$sending = $this->Sending->find(
			'first', 
			array(
				'recursive' => 0,
				'conditions' => array('Sending.id' => $id),
				'contain' => array('Mail', 'Smtp' => array('fields' => array('id', 'email')))
			)
		);
		
		$this->set('sending', $sending);
		
		$this->Sending->Recipient->Link->virtualFields['times'] = 'COUNT(url)';
		$this->set(
			'links',
			$this->Sending->Recipient->Link->find('all', array(
				'recursive' => -1,
				'fields' => array('url', 'times'),
				'conditions' => array('sending_id' => $id),
				'group' => array('url'),
				'order' => array('times DESC')
			))
		);
		unset($this->Sending->Recipient->Link->virtualFields['times']);
		
		$this->set('geo_data', $this->Sending->geoDataToChartData($this->Sending->getSendingGeoData($id)));
		$this->set('sended_recipients', $this->Sending->sendedRecipients($id));
		$this->set('opened_recipients', $this->Sending->openedRecipients($id));
		$this->set(
			'unsubscribed', 
			$this->Sending->Unsubscription->find(
				'count', 
				array(
					'recursive' => -1,
					'conditions' => array('sending_id' => $id),
					'group' => array('sending_id', 'member_id')
				)
			)
		);
		
		$this->set('mailinglists', $this->Sending->MailinglistsSending->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'sending_id' => $id
			)
		)));
		
		$this->set(
			'browsers',
			$this->Sending->Recipient->getBrowserStats($this->Auth->user('id'), $id)
		);
		
		$this->set(
			'devices',
			$this->Sending->Recipient->getDeviceStats($this->Auth->user('id'), $id)
		);
		
		$this->set(
			'oss',
			$this->Sending->Recipient->getOsStats($this->Auth->user('id'), $id)
		);
	}


	public function add($mail_id) {
	
		$this->Sending->Mail->recursive = -1;
		$mail = $this->Sending->Mail->read(null, $mail_id);
		
		if ($this->request->is('post')) {
			$this->Sending->getDataSource()->begin();
			$this->Sending->create();
			
			$mailinglist_ids = $this->request->data['Mailinglist']['Mailinglist'];
		
			if ($this->Sending->saveNew($this->request->data, $mail)) {
				if($this->Sending->saveAssociatedMailinglists($this->Sending->id, $mailinglist_ids)) {
					$this->Sending->getDataSource()->commit();
					$this->Session->setFlash(__("L'Invio è stato salvato"), 'default', array(), 'info');
					$this->redirect(array('controller' => 'mails', 'action' => 'view', $this->request->data['Sending']['mail_id']));
				}
				else {
					$this->Sending->getDataSource()->rollback();
					$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
				}
			} else {
				$this->Sending->getDataSource()->rollback();
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
		
		//se la mail non esiste
		if (!$this->Sending->Mail->exists($mail_id)) {
			$this->redirect(array('controller' => 'mails', 'action' => 'index'));
		}
		
		$this->set(
			'Mailinglist',
			$this->Sending->Recipient->Member->Mailinglist->find(
				'list', 
				array(
					'recursive' => -1, 
					'fields' => array('id', 'name'),
					'conditions' => array(
						'user_id' => $this->Auth->user('id')
					)
				)
			)
		);
		
		$this->set(
			'smtps',
			$this->Sending->Smtp->find(
				'list', 
				array(
					'recursive' => -1, 
					'fields' => array('id', 'email'),
					'conditions' => array(
						'user_id' => $this->Auth->user('id')
					)
				)
			)
		);
		
		$this->set('mail_id', $mail_id);
		$this->set('mail', $mail);
	}


	public function checkSendingStatus($id) {
		if(!$this->request->is('json')) {
			throw new MethodNotAllowedException();
		}
		$sending = $this->Sending->getSendingStatus($id);
		$this->set('sending', $sending);
		$this->set('_serialize', array('sending'));
	}
	
	


	public function checkSendings() {
	
		if(!$this->request->is('json')) {
			throw new MethodNotAllowedException();
		}
		$data = $this->Sending->getDashboardData($this->Auth->user('id'));
		//$sendings = $this->Sending->getOnSending($this->Auth->user('id'));
		$this->set('dashboard', $data);
		$this->set('_serialize', array('dashboard'));
	}
	
	
	
	
	public function delete($id = null, $mail_id = null) {
		$this->Sending->id = $id;
		$this->Sending->getDataSource()->begin();
		
		if(!$this->Sending->isInSending($id)) {
			if ($this->Sending->delete()) {
				$this->Sending->getDataSource()->commit();
				$this->Session->setFlash(__("L'Invio è stato eliminato"), 'default', array(), 'info');
			
			}
			else {
				$this->Sending->getDataSource()->rollback();
				$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
			}
		}
		else {
			$this->Session->setFlash(__('Impossibile eliminare un invio in corso.'), 'default', array(), 'error');
		}
		
		if($this->Sending->Mail->exists($mail_id))
			$this->redirect(array('controller' => 'mails', 'action' => 'view', $mail_id));
		else
			$this->redirect(array('controller' => 'mails', 'action' => 'index'));
		
	}
	
	public function resend($id = null) {
		$this->Sending->id = $id;
		
		if(!$this->Sending->resend($id))
			$this->Session->setFlash(__("Errore durante l'operazione"), 'default', array(), 'error');
		$this->redirect(array('action' => 'view', $id));
	}


	public function unsubscribed($id) {
	
		$this->set(
			'sending', 
			$this->Sending->find(
				'first', 
				array(
					'recursive' => 0,
					'conditions' => array('Sending.id' => $id),
					'contain' => array('Mail', 'Smtp' => array('fields' => array('id', 'email')))
				)
			)
		);
			
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE'
		);
		
		$this->paginate = array(
			'Unsubscription' => array( 
				'recursive' => -1,
				'conditions' => array('Unsubscription.sending_id' => $id)
			)
		);
		
		$this->set('unsubscribeds', $this->paginate('Unsubscription'));
	}
	
	
	public function bulk() {
	
		$result = false;
		$message = __('Errore durante l\'operazione.');
		if(!empty($this->request->data['Sending']['selected'])) {
			switch($this->request->data['Sending']['action']) {
				case 'bulkDelete':
					list($result, $message) = $this->Sending->bulkDelete($this->request->data['Sending']['selected']);
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
	
	
	protected function __securitySettings_bulk() {
		$this->request->onlyAllow('post');
		if(!isset($this->request->data['Sending']['action']))
			$this->request->data['Sending']['action'] = null;
		if(!isset($this->request->data['Sending']['selected']))
			$this->request->data['Sending']['selected'] = array();
			
		switch($this->request->data['Sending']['action']) {
			case 'bulkDelete':
				$this->Security->allowedControllers = array('mails');
				$this->Security->allowedActions = array('view');
				$this->request->data['Sending']['selected'] = explode(',', $this->request->data['Sending']['selected']);
				foreach($this->request->data['Sending']['selected'] as $selected) {
					$this->Xuser->checkPerm($this->Sending, $selected);
				}
			break;
		}
	}
	
	
	protected function __securitySettings_resend() {
		$this->request->onlyAllow('post');
		$this->Xuser->checkPerm($this->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_unsubscribed() {
		$this->Xuser->checkPerm($this->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_add() {
		$this->Xuser->checkPerm($this->Sending->Mail, isset($this->request->pass[0])?$this->request->pass[0]:null);
		if($this->request->is('post')) {
			$this->Xuser->checkPerm($this->Sending->Mail, $this->request->data['Sending']['mail_id']);
			if(isset($this->request->data['Sending']['smtp_id']) && !empty($this->request->data['Sending']['smtp_id'])) {
				$this->Xuser->checkPerm($this->Sending->Smtp, $this->request->data['Sending']['smtp_id']);
			}
			if(is_array($this->request->data['Mailinglist']['Mailinglist'])) {
				foreach($this->request->data['Mailinglist']['Mailinglist'] as $mailinglist) {
					$this->Xuser->checkPerm(
						$this->Sending->Recipient->Member->Mailinglist, 
						$mailinglist
					);
				}
			}
		}
	}
	
	public function __securitySettings_checkSendingStatus() {
		$this->Xuser->checkPerm($this->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
		$this->Security->csrfCheck = false;
	}
	
	public function __securitySettings_checkSendings() {
		$this->Security->csrfCheck = false;
	}
}
