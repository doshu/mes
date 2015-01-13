<?php
App::uses('AppController', 'Controller');
App::uses('ApiResponse', 'Network/Api');

class RestController extends AppController {

	public $uses = array('Rest', 'Member');

	/**
	 * autenticazione della richiesta
	 * @param clientkey codice client
	 * @param key salt criptato secondo la chiave
	 *
	 * se autenticato imposta $this->Rest->currentData con i dati della richiesta
	 */
	public function beforeFilter() {
	
		$this->Auth->allow();
		$this->Security->csrfCheck = false;
		$this->Security->validatePost = false;
		
		$this->RequestHandler->viewClassMap = array(
            'json' => 'JsonView',
            'xml' => 'XmlView',
        );
        
        $this->request->addDetector(
			'json', 
			array('callback' => function ($request) {
					return isset($request->params['ext']) && $request->params['ext'] == 'json';
				}
			)
		);
		
		$this->request->addDetector(
			'xml', 
			array('callback' => function ($request) {
					return isset($request->params['ext']) && $request->params['ext'] == 'xml';
				}
			)
		);
		
		
		if(
			(isset($this->request->params['named']['clientkey']) && !empty($this->request->params['named']['clientkey'])) &&
			(isset($this->request->params['named']['key']) && !empty($this->request->params['named']['key']))
		) {
			if(!$this->Rest->authenticate($this->request->params['named']['clientkey'], $this->request->params['named']['key'])) {
				
				throw new ForbiddenException();
			}
		}
		else {
			throw new ForbiddenException();
		}
		
		
		$this->response = new ApiResponse($this);
		
	}
	

	/**
	 * Api function
	 * return the full list of members of a user
	 *
	 */
	public function listMembers() {
		
		if($this->Rest->authorize('members.show')) {
			$this->response->setData($this->Member->find('all', array(
				'recursive' => -1,
				'conditions' => array(
					'user_id' => $this->Rest->currentData['Api']['user_id']
				) 
			)));
			$this->response->send();
		}
		else {
			throw new ForbiddenException();
		}
	}
	
	/**
	 * Api function
	 * create a new member 
	 */
	public function addMember() {
		
		if($this->Rest->authorize('members.create_edit')) {
		
			$this->Member->create();
			$this->Member->getDataSource()->begin();
		
			$this->request->data['Member']['secret'] = $this->Member->__generateNewSecret($this->data['Member']['email'].time());
			$this->request->data['Member']['user_id'] = $this->Rest->currentData['Api']['user_id'];
		
			$member = $this->Member->getByEmailForUser(
				$this->request->data['Member']['email'], 
				array(),
				-1,
				$this->Rest->currentData['Api']['user_id']
			);
		
		
			if(!empty($member)) {
				$this->response->statusCode(500);
				$this->response->addError(
					API_MEMBER_ALREADY_EXISTS, 
					array('email' => $this->request->data['Member']['email']), 
					__('Esiste già un membro con questa email')
				);
				$this->response->send();
			}
		
			if($this->Rest->authorize('members.subscribe_unsubscribe')) {
				if(isset($this->request->data['Mailinglist']['Mailinglist']) && is_array($this->request->data['Mailinglist']['Mailinglist'])) {
					foreach($this->request->data['Mailinglist']['Mailinglist'] as $mailinglist) {
						try {
							$this->Mailinglist->checkPerm($mailinglist, null, $this->Rest->currentData['Api']['user_id']);
						}
						catch(NotFoundException $e) {
							$this->response->statusCode(500);
							$this->response->addError(
								API_MAILINGLIST_NOT_EXISTS, 
								array('list' => $mailinglist), 
								__('La mailinglist non esiste')
							);
							$this->response->send();
						}
					}
				}
			}
			else {
				unset($this->request->data['Mailinglist']);
			}
		
			
			if (
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
						//'validate' => false
					)
				)
			) {
				$this->Member->getDataSource()->commit();
				$this->response->send();
			} else {
				$this->Member->getDataSource()->rollback();
				$this->response->statusCode(500);
				$this->response->addError(
					API_ERROR_DURING_MEMBER_SAVING, 
					array('data' => $this->request->data), 
					__('Errore durante il salvataggio')
				);
				$this->response->send();
			}
		}
		else {
			throw new ForbiddenException();
		}
	}
	
	/**
	 * Api functiom
	 * Edit member by email
	 */
	public function editMember() {
		
		if($this->Rest->authorize('members.create_edit')) { 
			$this->Member->getDataSource()->begin();
		
			$this->request->data['Member']['user_id'] = $this->Rest->currentData['Api']['user_id'];
		
			$member = $this->Member->getByEmailForUser(
				$this->request->data['Member']['email'], 
				array(),
				-1,
				$this->Rest->currentData['Api']['user_id']
			);
		
			if(empty($member)) {
				$this->response->statusCode(500);
				$this->response->addError(
					API_MEMBER_NOT_EXISTS, 
					array('email' => $this->request->data['Member']['email']), 
					__('Non esiste un membro con questa email')
				);
				$this->response->send();
			}
			
			$this->request->data['Member']['id'] = $member['Member']['id'];
		
			if($this->Rest->authorize('members.subscribe_unsubscribe')) {
				if(isset($this->request->data['Mailinglist']['Mailinglist']) && is_array($this->request->data['Mailinglist']['Mailinglist'])) {
					foreach($this->request->data['Mailinglist']['Mailinglist'] as $mailinglist) {
						try {
							$this->Mailinglist->checkPerm($mailinglist, null, $this->Rest->currentData['Api']['user_id']);
						}
						catch(NotFoundException $e) {
							$this->response->statusCode(500);
							$this->response->addError(
								API_MAILINGLIST_NOT_EXISTS, 
								array('list' => $mailinglist), 
								__('La mailinglist non esiste')
							);
							$this->response->send();
						}
					}
				}
			}
			else {
				unset($this->request->data['Mailinglist']);
			}
		
			if (
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
						//'validate' => false
					)
				)
			) {
				$this->Member->getDataSource()->commit();
				$this->response->send();
			} else {
				$this->Member->getDataSource()->rollback();
				$this->response->statusCode(500);
				$this->response->addError(
					API_ERROR_DURING_MEMBER_SAVING, 
					array('data' => $this->request->data), 
					__('Errore durante il salvataggio')
				);
				$this->response->send();
			}
		}
		else {
			throw new ForbiddenException();
		}
	}
	
	
	/**
	 * Api function
	 * Check if a member exists by email
	 */
	public function memberExists($email) {
	
		if($this->Rest->authorize(array('members.show', 'members.create_edit'))) {
			$this->response->setData($this->Member->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'user_id' => $this->Rest->currentData['Api']['user_id'],
					'email' => $email
				) 
			)));
			$this->response->send();
		}
		else {
			throw new ForbiddenException();
		}
	}
	
	
	/**
	 * Api function 
	 * Delete a member
	 */
	public function deleteMember($email) {
	
		if($this->Rest->authorize('members.delete')) {
		
			$member = $this->Member->getByEmailForUser(
				$email, 
				array(),
				-1,
				$this->Rest->currentData['Api']['user_id']
			);
			
			if(!empty($member)) {
				$this->Member->id = $member['Member']['id'];
				if($this->Member->delete()) {
					$this->response->send();
				}
				else {
					$this->response->statusCode(500);
					$this->response->addError(
						API_ERROR_DURING_MEMBER_DELETING, 
						array('email' => $email), 
						__('Errore durante l\'eliminazione')
					);
					$this->response->send();
				}
			}
			else {
				$this->response->statusCode(500);
				$this->response->addError(
					API_MEMBER_NOT_EXISTS, 
					array('email' => $email), 
					__('Non esiste un membro con questa email')
				);
				$this->response->send();
			}
		}
		else {
			throw new ForbiddenException();
		}
	}
	
	public function __securitySettings_addMember() {
		$this->Security->requirePost();
	}
	
	public function __securitySettings_editMember() {
		$this->Security->requirePost();
	}
	
	public function __securitySettings_deleteMember() {
		$this->Security->requirePost();
	}
}
