<?php
App::uses('AppController', 'Controller');


class MembersController extends AppController {


	public function index() {
	
		$this->Member->recursive = -1;
		$this->autoPaginate = true;
		
		$this->paginate = array('Member' => array('order' => array('Member.created' =>  'desc')));
		
		$this->set(
			'count', 
			$this->Member->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('Member.user_id' => $this->Auth->user('id'))
				)
			)
		);
		
		$this->set(
			'countValid', 
			$this->Member->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('user_id' => $this->Auth->user('id'), 'valid' => Member::isValid)
				)
			)
		);
		
		$memberAdditionalFields = $this->Member->getModelFields();
		$this->set('memberAdditionalFields', $memberAdditionalFields);
		
		$this->autoPaginateOp = array(
			'email' => 'LIKE',
			'created' => array('BETWEEN', array('type' => 'date', 'convert' => true, 'time' => true)),
			'valid' => '='
		);
		
		foreach($memberAdditionalFields as $additionalField) {
			if(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'date') {
				$this->autoPaginateOp[$additionalField['Memberfield']['code']] = array('BETWEEN', array('type' => 'date'));
			}
			else {
				$this->autoPaginateOp[$additionalField['Memberfield']['code']] = 'LIKE';
			}
		}
		
		$this->set(
			'members',
			$this->paginate('Member', array('Member.user_id' => $this->Auth->user('id')))
		);
	}

	

	public function view($id = null) {
		$this->Member->recursive = -1;
		$this->set('member', $this->Member->read(null, $id));
		$this->set('memberAdditionalFields', $this->Member->getModelFields());
		$this->set('unsubscribed', $this->Member->Unsubscription->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('member_id' => $id)
			)
		));
	}
	
	
	public function unsubscribed($id) {
	
		$this->set(
			'member', 
			$this->Member->find(
				'first', 
				array(
					'recursive' => -1,
					'conditions' => array('Member.id' => $id),
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
				'conditions' => array('Unsubscription.member_id' => $id)
			)
		);
		
		$this->set('unsubscribeds', $this->paginate('Unsubscription'));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Member->create();
			$this->Member->getDataSource()->begin();
			
			$this->request->data['Member']['secret'] = $this->Member->__generateNewSecret($this->data['Member']['email'].time());
			$this->request->data['MailinglistsMember']['created'] = '2014-05-05';
			if (
				$this->Member->validateOnCreate($this->request->data) && 
				$this->Member->saveAssociated(
					$this->request->data,
					array(
						'fieldList' => array(
							'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist', 'secret'),
							'MailinglistsMember' => array('mailinglist_id', 'member_id', 'created'),
							'Memberfieldvalue' => array(
								'id', 
								'memberfield_id', 
								'value_varchar',
								'value_text', 
								'value_boolean', 
								'value_date'
							)
						),
						'validate' => false
					)
				)
			) {
				$this->Member->getDataSource()->commit();
				$this->Session->setFlash(__('Il Membro è stato salvato'), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Member->getDataSource()->rollback();
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
		$mailinglists = $this->Member->Mailinglist->find(
			'list', 
			array('recursive' => -1, 'conditions' => array('user_id' => $this->Auth->user('id')))
		);
		$this->set('memberAdditionalFields', $this->Member->getModelFields());
		$this->set(compact('mailinglists'));
	}
	
	
	public function addQuick() {
		App::uses('SafeDateHelper', 'View/Helper');
		if($this->request->is('json') && $this->request->isPost()) {
			$this->Member->getDataSource()->begin();
			$this->request->data['Member']['secret'] = $this->Member->__generateNewSecret($this->data['Member']['email'].time());
			if($this->Member->validateOnCreate($this->request->data)) {
				if(
					$this->Member->save($this->request->data, true, 
						array(
							'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist', 'secret'),
							'MailinglistsMember' => array('mailinglist_id'),
						)
					)
				) {
					$this->Member->getDataSource()->commit();
					$new = $this->Member->find('first', array('recursive' => -1, 'conditions' => array('Member.id' => $this->Member->id)));
					$userTimezoneDate = DateTime::createFromFormat('Y-m-d H:i:s', $new['Member']['created']);
					$new['Member']['createdUserTimeZone'] =	SafeDateHelper::dateForUser($userTimezoneDate);
					$result = array('status' => 1, 'message' => __('Inserimento effettuato.'), 'new' => $new);
				}
				else {
					$this->Member->getDataSource()->rollback();
					$errors = array_values($this->Member->validationErrors);
					$result = array('status' => 0, 'message' => $errors[0]);
				}
			}
			else {
				$this->Member->getDataSource()->rollback();
				$error = isset($this->Member->validationErrors['email']) && !empty($this->Member->validationErrors['email'])?
					$this->Member->validationErrors['email']:__('Errore durante l\'inserimento.');
				$result = array('status' => 0, 'message' => $error);
			}
			
		}
		else {
			$result = array('status' => 0, 'message' => __('Errore inserimento.'));
		}
		$this->set('result', $result);
		$this->set('_serialize', array('result'));
	}
	
	
	public function edit($id = null) {

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Member->getDataSource()->begin();
			if (
				$this->Member->validateOnEdit($this->request->data) && 
				$this->Member->saveAssociated(
					$this->request->data,
					array(
						'fieldList' => array(
							'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist', 'secret'),
							'MailinglistsMember' => array('mailinglist_id'),
							'Memberfieldvalue' => array(
								'id', 
								'memberfield_id', 
								'value_varchar',
								'value_text', 
								'value_boolean', 
								'value_date'
							)
						),
						'validate' => false
					)
				)
				
			) {
				$this->Member->getDataSource()->commit();
				$this->Session->setFlash(__('Il Membro è stato salvato'), 'default', array(), 'info');
				if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from']))
					$this->redirect(array('action' => 'mailinglist', $this->request->params['named']['from']));	
				
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Member->getDataSource()->rollback();
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		} else {
			
			$this->request->data = $this->Member->find(
				'first', 
				array(
					'conditions' => array('Member.id' => $id),
					'recursive' => -1,
					'contain' => array('Mailinglist')
				)
			);
			
		}
		$mailinglists = $this->Member->Mailinglist->find('list');
		
		
		$this->set('memberAdditionalFields', $this->Member->getModelFields());
		$this->set(compact('mailinglists'));
	}


	public function delete($id = null) {
		$this->Member->id = $id;

		if ($this->Member->delete()) {
			$this->Session->setFlash(__('Il Membro è stato eliminato'), 'default', array(), 'info');
			if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from']))
				$this->redirect(array('controller' => 'mailinglists', 'action' => 'view', $this->request->params['named']['from']));	
				
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		$this->redirect($this->referer(array('action' => 'index'), true));
	}
	
	public function mailinglist($list_id) {
	
		
		$this->Member->Mailinglist->id = $list_id;
		$this->Member->recursive = -1;
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'email' => 'LIKE',
			'created' => array('BETWEEN', array('type' => 'date', 'convert' => true, 'time' => true))
		);
		
		$memberAdditionalFields = $this->Member->getModelFields();
		$this->set('memberAdditionalFields', $memberAdditionalFields);
		
		foreach($memberAdditionalFields as $additionalField) {
			if(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'date') {
				$this->autoPaginateOp[$additionalField['Memberfield']['code']] = array('BETWEEN', array('type' => 'date'));
			}
			else {
				$this->autoPaginateOp[$additionalField['Memberfield']['code']] = 'LIKE';
			}
		}
		
		if (!$this->Member->Mailinglist->exists()) {
			throw new NotFoundException(__('Invalid mailinglist'));
		}
		
		$this->set(
			'mailinglist', 
			$this->Member->Mailinglist->find(
				'first',
				array(
					'recursive' => -1,
					'conditions' => array('id' => $list_id)
				)
			)
		);
		
		
		$this->paginate = array(
			'Member' => array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'mailinglists_members',
						'alias' => 'MailinglistsMember',
		    			'type' => 'LEFT',
						'conditions' => array(
							'MailinglistsMember.member_id = Member.id',
						)
					)
				),
				'order' => array('Member.created' => 'desc')
			)
		);
		
		$this->set(
			'members',
			$this->paginate('Member', array('MailinglistsMember.mailinglist_id' => $list_id))
		);
		
		$this->set(
			'count',
			$this->Member->find(
				'count',
				array(
					'recursive' => -1,
					'joins' => array(
						array(
							'table' => 'mailinglists_members',
							'alias' => 'MailinglistsMember',
							'type' => 'INNER',
							'conditions' => array(
								'MailinglistsMember.member_id = Member.id',
							)
						)
					),
					'conditions' => array('MailinglistsMember.mailinglist_id' => $list_id)
				)
			)	
		);
		
		$this->set(
			'countValid',
			$this->Member->find(
				'count',
				array(
					'recursive' => -1,
					'joins' => array(
						array(
							'table' => 'mailinglists_members',
							'alias' => 'MailinglistsMember',
							'type' => 'INNER',
							'conditions' => array(
								'MailinglistsMember.member_id = Member.id',
							)
						)
					),
					'conditions' => array(
						'MailinglistsMember.mailinglist_id' => $list_id,
						'Member.valid' => Member::isValid
					)
				)
			)	
		);
		
	}
	
	
	public function import() {
		
		if($this->request->isPost()) {
			App::uses('Member', 'Model');
					
			$importExists = (isset($this->request->data['Member']['importExists']) && $this->request->data['Member']['importExists']); 
			
			$this->Member->getDataSource()->begin();
			
			$error = $this->Member->validateImportFile($this->request->data['Member']['file'], $importExists);
			
			if(empty($error)) {
			
				$import = $this->Member->importFile(
					$this->request->data['Member']['file']['tmp_name'], 
					$importExists
				);

				if($import['status']) {
					$this->Member->getDataSource()->commit();
					$this->redirect(array('controller' => 'members', 'action' => 'index'));
				}
				else {
					$this->Member->getDataSource()->rollback();
					$this->set('errors', $import['errors']);
					$this->Session->setFlash(__("Errore durante l'importazione"), 'default', array(), 'error');
				}
			}
			else {
				$this->Member->getDataSource()->rollback();
				$this->set('errors', $error);
				$this->Session->setFlash(__("Errore durante l'importazione"), 'default', array(), 'error');
			}
			
		}
	}
	
	
	public function unsubscribe() {
		
		App::uses('Url', 'Utility');
		
		if(
			isset($this->request->query['recipient']) && 
			isset($this->request->query['key']) && 
			isset($this->request->query['sending'])
		) {
		
			$recipient = $this->Member->Recipient->find('first',
				array(
					'recursive' => 2,
					'conditions' => array(
						'Recipient.id' => $this->request->query['recipient'],
						'Recipient.member_secret' => $this->request->query['key'],
						'Recipient.sending_id' => $this->request->query['sending'],
						'Recipient.id <>' => null,
						'Recipient.member_secret <>' => null,
						'Recipient.sending_id <>' => null,
					),
					'contain' => array(
						'Member',
						'Sending' => array('Mailinglist'),
					)
				)
			);
			
			
			
			if(
				isset($recipient['Member']['id']) && !empty($recipient['Member']['id']) &&
				isset($recipient['Sending']['id']) && !empty($recipient['Sending']['id']) && 
				$recipient['Member']['email'] == $recipient['Recipient']['member_email']
			) {
				
				$mailinglists = (array)Hash::extract($recipient['Sending']['Mailinglist'], '{n}.id');
		
				$result = $this->Member->unsubscribeMember(
					$recipient, 
					$mailinglists
				);
				
				
				if(isset($this->request->query['redirect']) && Url::isAbsolute($this->request->query['redirect'])) {
					$redirect = $this->request->query['redirect'];
				}
				else {
					$redirect = '/unsubscribeResult';
				}
				$redirect = Url::appendParams($redirect, array('status='.$result));
				$this->redirect($redirect);
			}
		}
		
		$this->redirect('/brokenLink');
	}
	
	public function export($list = null) {
		
		$csv = $this->Member->getCsv($this->Auth->user('id'));
		$this->response->body($csv);
		$this->response->type('text/csv');
		$this->response->download('export_all_members.csv');

		return $this->response;

	}
	

	public function example() {
		$this->response->type('csv');
		$this->response->body($this->Member->getImportExample());
		$filename = __('esempio_importazione');
		$this->response->download($filename.'.csv');
		return $this->response;
	}
	
	
	public function bulk() {
	
		$result = false;
		$message = __('Errore durante l\'operazione.');
		if(!empty($this->request->data['Member']['selected'])) {
			switch($this->request->data['Member']['action']) {
				case 'bulkDelete':
					list($result, $message) = $this->Member->bulkDelete($this->request->data['Member']['selected']);
				break;
				case 'bulkUnsubscribe':
					list($result, $message) = $this->Member->bulkUnsubscribe(
						$this->request->data['Member']['selected'], 
						$this->request->data['Member']['mailinglist']
					);
				break;
				case 'bulkValidate':
					$this->Member->Behaviors->disable('Eav');
				
					$this->set(
						'members', 
						$this->Member->find('all', array(
							'recursive' => -1,
							'conditions' => array(
								'id' => $this->request->data['Member']['selected']
							)
						))
					);
					if(isset($this->params['named']['from']) && !empty($this->params['named']['from'])) {
						$this->set('from', $this->params['named']['from']);
						if(isset($this->params['named']['scope']) && !empty($this->params['named']['scope'])) {
							$this->set('scope', $this->params['named']['scope']);
						}
					}
					else {
						$this->set('from', 'members');
					}
					$this->render('validate');
					return true;
					
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
	
	
	public function validateMember($id) {
		App::import('Vendor', 'Mailer');
		$Mailer = new Mailer();
		$this->Member->recursive = -1;
		$member = $this->Member->read(null, $id);
		$status = $Mailer->smtpCheckAddress($member['Member']['email']);
		$this->Member->set('valid', $status);
		$this->set('_serialize', array('status'));
		if($this->Member->save()) {
			$this->set('status', $status);
		}
		else {
			throw new InternalErrorException('Cannot Save');
		}
	}
	
	
	protected function __securitySettings_bulk() {
	
		$this->request->onlyAllow('post');
		if(!isset($this->request->data['Member']['action']))
			$this->request->data['Member']['action'] = null;
		if(!isset($this->request->data['Member']['selected']))
			$this->request->data['Member']['selected'] = array();
		
		switch($this->request->data['Member']['action']) {
			case 'bulkDelete':
				$this->Security->allowedControllers = array('members');
				$this->Security->allowedActions = array('index');
				$this->request->data['Member']['selected'] = explode(',', $this->request->data['Member']['selected']);
				foreach($this->request->data['Member']['selected'] as $selected) {
					$this->Xuser->checkPerm($this->Member, $selected);
				}
			break;
			case 'bulkValidate':
				$this->Security->allowedControllers = array('members');
				$this->Security->allowedActions = array('index');
				$this->request->data['Member']['selected'] = array_filter(explode(',', $this->request->data['Member']['selected']));
				foreach($this->request->data['Member']['selected'] as $selected) {
					$this->Xuser->checkPerm($this->Member, $selected);
				}
			break;
			case 'bulkUnsubscribe':
				if(isset($this->request->data['Member']['mailinglist'])) {
					$this->Security->allowedControllers = array('members');
					$this->Security->allowedActions = array('mailinglists');
					$this->request->data['Member']['selected'] = explode(',', $this->request->data['Member']['selected']);
					foreach($this->request->data['Member']['selected'] as $selected) {
						$this->Xuser->checkPerm($this->Member, $selected);
					}
					$this->Xuser->checkPerm($this->Member->Mailinglist, $this->request->data['Member']['mailinglist']);
				}
				else
					$this->Security->blackHole($this, 'auth');
			break;
		}
		
	}
	
	protected function __securitySettings_index() {
		//$this->Security->unlockField('id.');
	}
	
	
	protected function __securitySettings_unsubscribe() {
		Configure::write('no_check_cookie', true);
		$this->Auth->allow('unsubscribe');
	}
	
	protected function __securitySettings_mailinglist() {
		$this->Xuser->checkPerm($this->Member->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_export() {
		$this->Security->requirePost();
	}
	
	protected function __securitySettings_addQuick() {
		if($this->request->is('json') && $this->request->isPost()) {
			$this->Xuser->checkPerm($this->Member->Mailinglist, $this->request->data['Mailinglist']['Mailinglist']);
		}
		$this->Security->useOnce = false;
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_unsubscribed() {
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_validateMember() {
		$this->request->onlyAllow('post');
		$this->Security->csrfUseOnce = false;
		$this->Security->validatePost = false;
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
