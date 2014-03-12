<?php
App::uses('AppModel', 'Model');


class Sending extends AppModel {

	public $actsAs = array('Geo');

	public $validate = array(
		'mail_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'time' => array(
			'timestamp' => array(
				'rule' => 'isValidTimestamp',
    			'message' => 'Questo campo deve avere un formato data e ora valido',
    			'allowEmpty' => true
			)
		),
		'type' => array(
			'valid' => array(
				'rule' => 'isValidType',
				'message' => 'Questo campo deve avere un tipo valido',
			)
		)
	);



	public $belongsTo = array(
		'Mail' => array(
			'className' => 'Mail',
			'foreignKey' => 'mail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Smtp' => array(
			'className' => 'Smtp',
			'foreignKey' => 'smtp_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public $hasMany = array(
		'Recipient' => array(
			'className' => 'Recipient',
			'foreignKey' => 'sending_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Unsubscription' => array(
			'className' => 'Unsubscription',
			'foreignKey' => 'sending_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	
	public $hasAndBelongsToMany = array(
		'Mailinglist' => array(
			'className' => 'Mailinglist',
			'joinTable' => 'mailinglists_sendings',
			'foreignKey' => 'sending_id',
			'associationForeignKey' => 'mailinglist_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	public static $WAITING = 0;
	public static $SENDING = 1;
	public static $COMPLETED = 2;
	public static $ABORTED = 3;
	
	public static $HTML = 0;
	public static $TEXT = 1;
	public static $BOTH = 2;
	
	
	public function saveNew($data) {
		
		if(empty($data['Mailinglist']['Mailinglist'])) {
			$this->validationErrors['Mailinglist'] = __('Devi selezionare almeno una lista di destinatari');
			return false;
		}
		
		
		
		if(!$this->Mail->exists($data['Sending']['mail_id'])) {
			return false;
		}
		if(!$this->Smtp->exists($data['Sending']['smtp_id'])) {
			$this->validationErrors['Sending']['smtp_id'] = __('Devi selezionare un indirizzo di invio');
			return false;
		}
		
		if(isset($data['Sending']['enableTime']) && $data['Sending']['enableTime'] == 1) {
			$data['Sending']['time'] = DateTime::createFromFormat(
				'd/m/Y H:i', 
				$data['Sending']['time'], 
				new DateTimeZone(AuthComponent::user('timezone'))
			);
			$data['Sending']['time'] = is_object($data['Sending']['time'])?$data['Sending']['time']->getTimestamp():false;
			unset($data['Sending']['enableTime']);
		}
		else {
			unset($data['Sending']['enableTime']);
			unset($data['Sending']['time']);
		}
		
		$data['Recipient'] = $this->__extractRecipients($data['Mailinglist']['Mailinglist']);
		$data['Sending']['recipient_count'] = count($data['Recipient']);
		unset($data['Mailinglist']['Mailinglist']);
		
		$data['Sending']['smtp_email'] = $this->Smtp->field('email', array('Smtp.id' => $data['Sending']['smtp_id']));
		$data['Sending']['status'] = 0;
		
		return $this->saveAssociated($data);
	}
	
	
	public function saveAssociatedMailinglists($sending_id, $mailinglist_ids) {
		foreach($mailinglist_ids as $mailinglist_id) {
			$mailinglist_data = $this->Mailinglist->read('name', $mailinglist_id);
			$this->MailinglistsSending->create();
			if(!$this->MailinglistsSending->save(array(
				'sending_id' => $sending_id,
				'mailinglist_id' => $mailinglist_id,
				'mailinglist_name' => $mailinglist_data['Mailinglist']['name']
			))) {
				return false;
			}
		}
		return true;
	}	
	
	
	private function __extractRecipients($mailinglists) {
		$recipients = array();
		$return = array();
		if(!empty($mailinglists)) {
			$this->virtualFields['member_id'] = 'DISTINCT Member.id';
			
			$customFields = $this->Recipient->Member->getModelFields();
			$customFields = Hash::extract($customFields, '{n}.Memberfield.code');
			
			$recipients = $this->Recipient->Member->find(
				'all',
				array(
					'recursive' => -1,
					'joins' => array(
						array(
							'table' => 'mailinglists_members',
        					'alias' => 'MailinglistsMember',
        					'type' => 'LEFT',
        					'conditions' => array(
            					'Member.id = MailinglistsMember.member_id',
        					)
   						)
					),
					'conditions' => array(
						'MailinglistsMember.mailinglist_id' => $mailinglists
					),
					'fields' => array_map(
						function($el) {
							return 'Member.'.$el;
						},
						array_merge(
							array(
								'id',
								'email',
								'secret',
							),
							$customFields
						)
					)
				)
			);
			
			
			foreach($recipients as $recipient) {
				$return[] = array(
					'member_id' => $recipient['Member']['id'],
					'member_email' => $recipient['Member']['email'],
					'member_secret' => $recipient['Member']['secret'],
					'member_data' => json_encode($recipient['Member']),
				);			
			}
			
		}
		$this->virtualFields = array();
		return $return;
	}
	
	
	public function countRecipients($id) {
		return $this->Recipient->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('sending_id' => $id)
			)
		);
	}
	
	
	public function sendedRecipients($id, $withError = true) {
		
		$conditions = array(
			'sending_id' => $id,
			'sended' => 1,
		);
		
		if(!$withError) {
			$conditions['errors'] = 0;
		}
		
		return $this->Recipient->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => $conditions
			)
		);
	}
	
	public function openedRecipients($id) {
		return $this->Recipient->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array(
					'sending_id' => $id,
					'sended' => 1,
					'opened' => 1
				)
			)
		);
	}
	
	public function getWaitings($user_id) {
		
		App::uses('SafeDateHelper', 'View/Helper');
		$mails = $this->Mail->getUserMailId($user_id);
		
		$waitings = array();
		if(!empty($mails)) {
			$waitings = $this->find(
				'all',
				array(
					'recursive' => 2,
					'conditions' => array(
						'mail_id' => $mails,
						'Sending.status' => self::$WAITING
					),
					'contain' => array(
						'Mail'
					)
				)
			);
			
			foreach($waitings as &$waiting) { //add date parsed with user timezone
				$waiting['Sending']['created_client'] = SafeDateHelper::dateForUser(new DateTime($waiting['Sending']['created']));
				if(empty($waiting['Sending']['time'])) {
					$time = DateTime::createFromFormat('Y-m-d H:i:s', $waiting['Sending']['created']);
				}
				else {
					$time = new DateTime();
					$time->setTimestamp($waiting['Sending']['time']);	
				}
				$waiting['Sending']['time_client'] = SafeDateHelper::dateForUser($time);
			}
		} 
		return $waitings;
	}
	
	
	public function getSendingStatus($id) {
		
		$sending = $this->find(
			'first',
			array(
				'recursive' => 2,
				'contain' => array(
					'Mail'
				),
				'conditions' => array(
					'Sending.id' => $id,
				)
			)
		);
		
		$this->Recipient->virtualFields['total'] = 'COUNT(id)';
		$this->Recipient->virtualFields['sended'] = 'SUM(sended)'; //sended è 1 o 0 quindi la somma da il numero si inviati
		$this->Recipient->virtualFields['opened'] = 'SUM(opened)';
		$recipient = $this->Mail->Sending->Recipient->find(
			'all',
			array(
				'recursive' => -1,
				'conditions' => array('sending_id' => $sending['Sending']['id']),
				'fields' => array('total', 'sended', 'opened')
			)
		);
		$sending['Recipient'] = isset($recipient[0])?$recipient[0]['Recipient']:array();
		$this->Recipient->virtualFields = array();
		
		return $sending;
	}
	
	
	public function getOnSending($user_id) {
		
		$mails = $this->Mail->getUserMailId($user_id);
		
		$sendings = array();
		
		if(!empty($mails)) {
			$this->Recipient->virtualFields['total'] = 'COUNT(id)';
			$this->Recipient->virtualFields['done'] = 'SUM(sended)'; //sended è 1 o 0 quindi la somma da il numero si inviati
			$this->Recipient->virtualFields['opened'] = 'SUM(opened)';
			$this->Recipient->virtualFields['withError'] = 'SUM(errors)';
			$sendings = $this->find(
				'all',
				array(
					'recursive' => 2,
					'contain' => array(
						'Mail'
					),
					'conditions' => array(
						'Sending.mail_id' => $mails,
						'Sending.status' => self::$SENDING
					)
				)
			);
			
			foreach($sendings as &$sending) {
				$recipient = $this->Mail->Sending->Recipient->find(
					'all',
					array(
						'recursive' => -1,
						'conditions' => array('sending_id' => $sending['Sending']['id']),
						'fields' => array('total', 'done', 'opened', 'withError')
					)
				);
				$sending['Recipient'] = isset($recipient[0])?$recipient[0]['Recipient']:array();
			}

			$this->Recipient->virtualFields = array();		
		}
		
		//debug($sendings); exit;
		return $sendings;
	}
	
	public function isValidType($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		
		return in_array($data, array(self::$HTML, self::$TEXT, self::$BOTH));
	}
	
	public function isValidTimestamp($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		$timestamp = new DateTime();
		$timestamp = $timestamp->setTimestamp($data);
		return ($data != false && $timestamp instanceof DateTime);
	}
	
	public function isValidDateTime($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		return ($data instanceof DateTime);
	}
	
	
	public function resend($id) {
		
		$dataSource = $this->getDataSource();
		$dataSource->begin();
		try {
			$sendingSmtp = $this->read('smtp_id', $id);
			if(!$this->Smtp->exists($sendingSmtp['Sending']['smtp_id'])) {
				throw new Exception();
			}
			$this->Recipient->updateAll(array('errors' => 0, 'errors_details' => null), array('sending_id' => $id));
			$this->id = $id;
			$this->save(array('errors' => 0, 'status' => Sending::$WAITING));
			
			$dataSource->commit();
			return true;
		}
		catch(Exception $e) {
			
			$dataSource->rollback();
			return false;
		}
	}
	
	
	public function isInSending($id) {
		return (bool)$this->find('count', array(
			'recursive' => -1,
			'conditions' => array(
				'id' => $id,
				'status' => self::$SENDING
			)
		));
	}
	
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if($this->isInSending($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Alcuni Invii sono attualmente in corso.'));
			}
			else {
				if(!$this->delete($id)) {
					$dbo->rollback();
					return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcune Invii.'));
				}
			}
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
		$data = $this->read(null, $id);
		if(isset($data['Mail']['user_id']) && !empty($data['Mail']['user_id']))
			return $data['Mail']['user_id'] == $userId;
		throw new NotFoundException();
	}
	
	
	public function getDashboardData($user_id) {
		return array(
			'waiting' => $this->getWaitings($user_id),
			'sending' => $this->getOnSending($user_id),
			'complete' => array()
		);
	}
	
	
	public function getSendingGeoData($id) {
		
		return $this->Recipient->getGeoStatsBySending($id);
		
	}
	
	

}
