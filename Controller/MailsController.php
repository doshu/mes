<?php
App::uses('AppController', 'Controller');
/**
 * Mails Controller
 *
 * @property Mail $Mail
 */
class MailsController extends AppController {


	public function dashboard() {
		
		
		$this->set(
			'allmail',
			$this->Mail->find(
				'count', 
				array(
					'recursive' => -1,
					'conditions' => array('Mail.user_id' => $this->Auth->user('id'))
				)
			)
		);
		
		$this->set(
			'allsending',
			$this->Mail->Sending->find(
				'count', 
				array(
					'conditions' => array('Mail.user_id' => $this->Auth->user('id'))
				)
			)
		);
		
		$this->set(
			'mailinglistCount', 
			$this->Mail->Sending->Recipient->Member->Mailinglist->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('Mailinglist.user_id' => $this->Auth->user('id'))
				)
			)
		);
		
		$this->set('sendings', $this->Mail->Sending->getOnSending($this->Auth->user('id')));
		$this->set('waitings', $this->Mail->Sending->getWaitings($this->Auth->user('id')));
	}


	public function index() {
	
		$this->Mail->recursive = 0;
		
		$this->Mail->virtualFields['allmail'] = 'COUNT(DISTINCT Mail.id)';
		$this->Mail->virtualFields['allsending'] = 'COUNT(Sending.id)';
		
		$count = $this->Mail->find(
			'first',
			array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'sendings',
						'alias' => 'Sending',
						'type' => 'LEFT',
						'conditions' => array(
							'Mail.id = Sending.mail_id'
						)
					)
				),
				'fields' => array('allmail', 'allsending'),
				'conditions' => array('Mail.user_id' => $this->Auth->user('id'))
			)
		);
		
		$this->Mail->virtualFields = array();
		$this->set('count', $count['Mail']);
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'name' => 'LIKE',
			'description' => 'LIKE',
			'created' => array('BETWEEN', array('convert' => true, 'type' => 'date', 'time' => true))
		);
		
		$this->paginate = array('Mail' => array('recursive' => -1, 'order' => array('Mail.created' =>  'desc')));
		$this->set('mails', $this->paginate('Mail', array('Mail.user_id' => $this->Auth->user('id'))));
		
	}


	public function choose() {
		$this->set('personal_templates', $this->Mail->User->Template->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'user_id' => $this->Auth->user('id')
			),
			'order' => 'name ASC'
		)));
	}

	public function view($id = null) {

		$mail = $this->Mail->find(
			'first', 
			array(
				'conditions' => array('Mail.id' => $id), 
				'recursive' => 2,
				'contain' => array(
					'Attachment',
				)
			)
		);
		
		$this->paginate = array(
			'Sending' => array(
				'order' => array('created DESC'),
			)
		);
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'note' => 'LIKE',
			'recipient_count' => 'BETWEEN',
			'created' => array('BETWEEN', array('convert' => true, 'type' => 'date', 'time' => true)),
			'smtp_email' => 'LIKE',
			'status' => 'LIKE'
		);
		
		$sendings = $this->paginate('Sending', array('mail_id' => $id));
		
		
		//$mail['Sending'] = $sendings;
		$this->set('sendings', $sendings);
		$this->set('mail', $mail);
	}


	public function add() {
		
		if ($this->request->is('post')) {
			$this->Mail->create();
			if(
				(
					!isset($this->request->data['Attachment']) ||
					$this->request->data['Attachment'] = $this->Mail->Attachment->parseMailAttachment(
						$this->request->data['Attachment'],
						$this->Auth->user('id')
					)
				)
				
				&& $this->Mail->saveAssociated(
					$this->request->data, 
					array(
						'fieldList' => array(
							'Mail' => array('name', 'description', 'html', 'text', 'created', 'modified', 'user_id', 'subject'),
							'Attachment' => array('realname', 'path', 'mail_id')
						)
					)
				)
			) {
				$this->Session->setFlash(__("L'Email è stata salvata"), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
		else {
			if(isset($this->request->params['named']['templatetype']) && $this->request->params['named']['templatetype'] == 'personal') {
				if(isset($this->request->params['named']['template']) && !empty($this->request->params['named']['template'])) {
					$this->Mail->User->Template->recursive = -1;
					$pt = $this->Mail->User->Template->read(null, $this->request->params['named']['template']);
					$this->request->data['Mail']['html'] = $pt['Template']['html'];
					$this->request->data['Mail']['text'] = $pt['Template']['text'];
				}
			} 
		}
		$this->set('member_custom_fields', $this->Mail->Sending->Recipient->Member->getModelFields());
	}


	public function edit($id = null) {
		
		if($this->Mail->isInSending($id)) {
			$this->Session->setFlash(
				__('Attendere il completamento di tutti gli invii prima di modificare questa Email.'), 
				'default', 
				array(), 
				'error'
			);
			$this->redirect(array('action' => 'view', $id));
			
		}	
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if(
				(
					!isset($this->request->data['Attachment']) ||
					$this->request->data['Attachment'] = $this->Mail->Attachment->parseMailAttachment(
						$this->request->data['Attachment'],
						$this->Auth->user('id')
					)
				)
				
				&& $this->Mail->saveAssociated(
					$this->request->data, 
					array(
						'fieldList' => array(
							'Mail' => array('name', 'description', 'html', 'text', 'created', 'modified', 'user_id', 'subject'),
							'Attachment' => array('realname', 'path', 'mail_id')
						)
					)
				)
			) {
				$this->Session->setFlash(__("L'Email è stata salvata"), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		} 
		else {
			$this->request->data = $this->Mail->find('first', array('conditions' => array('Mail.id' => $id)));
		}
		$this->set('member_custom_fields', $this->Mail->Sending->Recipient->Member->getModelFields());
	}

	public function delete($id = null) {
	
		$this->Mail->id = $id;
		
		$inSending = $this->Mail->isInSending($id);
		
		
		if(!$inSending) {
			if ($this->Mail->delete()) {
				$this->Session->setFlash(__("L'Email è stata eliminata"), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			}
			else
				$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		}
		else {
			$this->Session->setFlash(
				__('Attendere il completamento di tutti gli invii prima di eliminare questa Email.'), 
				'default', 
				array(), 
				'error'
			);
		}
		
		$this->redirect($this->referer(array('action' => 'view', $this->Mail->id), true));
	}
	
	
	public function stats() {
	
		$this->set(
			'usage',
			$this->Mail->Sending->Recipient->getUsageStats($this->Auth->user('id'))
		);
		
		$this->set(
			'browsers',
			$this->Mail->Sending->Recipient->getBrowserStats($this->Auth->user('id'))
		);
		
		$this->set(
			'devices',
			$this->Mail->Sending->Recipient->getDeviceStats($this->Auth->user('id'))
		);
		
		$this->set(
			'oss',
			$this->Mail->Sending->Recipient->getOsStats($this->Auth->user('id'))
		);
		
		$this->set(
			'geo',
			$this->Mail->Sending->Recipient->geoDataToChartData(
				$this->Mail->Sending->Recipient->getGeoStatsByUser($this->Auth->user('id'))
			)
		);
	}
	
	public function bulk() {
	
		$result = false;
		$message = __('Errore durante l\'operazione.');
		if(!empty($this->request->data['Mail']['selected'])) {
			switch($this->request->data['Mail']['action']) {
				case 'bulkDelete':
					list($result, $message) = $this->Mail->bulkDelete($this->request->data['Mail']['selected']);
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
		$this->Mail->recursive = -1;
		$this->set('mail', $this->Mail->read(null, $id));
	}
	
	
	protected function __securitySettings_bulk() {
		$this->request->onlyAllow('post');
		
		if(!isset($this->request->data['Mail']['action']))
			$this->request->data['Mail']['action'] = null;
		if(!isset($this->request->data['Mail']['selected']))
			$this->request->data['Mail']['selected'] = array();
			
		switch($this->request->data['Mail']['action']) {
			case 'bulkDelete':
				$this->Security->allowedControllers = array('mails');
				$this->Security->allowedActions = array('index');
				$this->request->data['Mail']['selected'] = explode(',', $this->request->data['Mail']['selected']);
				foreach($this->request->data['Mail']['selected'] as $selected) {
					$this->Xuser->checkPerm($this->Mail, $selected);
				}
			break;
		}
	}
	
	protected function __securitySettings_add() {
		$this->Security->unlockedFields = array('Attachment..path', 'Attachment.path');
		if(isset($this->request->params['named']['templatetype']) && $this->request->params['named']['templatetype'] == 'personal') {
			if(isset($this->request->params['named']['template']) && !empty($this->request->params['named']['template'])) {
				$this->Xuser->checkPerm($this->Mail->User->Template, $this->request->params['named']['template']);
			}
		} 
	}
	
	protected function __securitySettings_edit() {
		$this->Security->unlockedFields = array('Attachment..path', 'Attachment.path');
		$this->Xuser->checkPerm($this->Mail, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Mail, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_preview() {
		$this->Xuser->checkPerm($this->Mail, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->Xuser->checkPerm($this->Mail, isset($this->request->pass[0])?$this->request->pass[0]:null);
		$this->request->onlyAllow('post', 'delete');
	}
}
