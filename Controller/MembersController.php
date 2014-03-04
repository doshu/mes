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
		
		$memberAdditionalFields = $this->Member->getModelFields();
		$this->set('memberAdditionalFields', $memberAdditionalFields);
		
		$this->autoPaginateOp = array(
			'email' => 'LIKE',
			'created' => array('BETWEEN', array('type' => 'date', 'convert' => 1))
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
		$this->set('member', $this->Member->find('first', array('conditions' => array('Member.id' => $id))));
		$this->set('memberAdditionalFields', $this->Member->getModelFields());
		$this->set('unsubscribed', $this->Member->Unsubscription->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('member_id' => $id)
			)
		));
		
		if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
			$this->set(
				'mailinglist', 
				$this->Member->Mailinglist->find(
					'first', 
					array('recursive' => -1, 'conditions' => array('id' => $this->request->params['named']['from']))
				)
			);
		}
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
			if (
				$this->Member->validateOnCreate($this->request->data) && 
				$this->Member->saveAssociated(
					$this->request->data,
					array(
						'fieldList' => array(
							'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist'),
							'MailinglistsMember' => array('mailinglist_id', 'member_id'),
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
		if($this->request->is('json') && $this->request->isPost() ) {
			$this->Member->getDataSource()->begin();
			if($this->Member->Mailinglist->exists($this->request->data['Mailinglist']['Mailinglist'])) {
				$exists = $this->Member->getByEmailForCurrentUser($this->request->data['Member']['email']);
				
				if(empty($exists)) {
					if(
						$this->Member->save($this->request->data, true, 
							array(
								'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist'),
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
					$result = array('status' => 0, 'message' => __('Questa email è già utilizzata da un altro membro.'));
				}
			}
			else {
				$this->Member->getDataSource()->rollback();
				$result = array('status' => 0, 'message' => __('La mailinglist specificata non è valida.'));
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
							'Member' => array('email', 'created', 'modified', 'user_id', 'Mailinglist'),
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
		if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
			$this->set('mailinglist', $this->Member->Mailinglist->find('first', array('recursive' => -1, 'conditions' => array('id' => $this->request->params['named']['from']))));
		}
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
			'email' => 'LIKE'
		);
		
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
							'type' => 'LEFT',
							'conditions' => array(
								'MailinglistsMember.member_id = Member.id',
							)
						)
					),
					'conditions' => array('MailinglistsMember.mailinglist_id' => $list_id)
				)
			)	
		);
		
		
		$this->set('memberAdditionalFields', $this->Member->getModelFields());
		
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
	
	protected function __securitySettings_index() {
		//$this->Security->unlockField('id.');
	}
	
	protected function __securitySettings_unsubscribe() {
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
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Member, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
