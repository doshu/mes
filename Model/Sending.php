<?php
App::uses('AppModel', 'Model');


class Sending extends AppModel {

	public $actsAs = array('Geo');

	public $validate = array(
		'mail_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
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
	
	
	public function saveNew($data, $mail) {
	
		switch($data['Sending']['type']) {
			case self::$HTML:
				if(empty($mail['Mail']['html'])) {
					$this->validationErrors['type'] = __('Per inviare questa Email in formato HTML, il campo HTML della Email non può essere vuoto.');
					return false;
				}
			break;
			case self::$TEXT:
				if(empty($mail['Mail']['text'])) {
					$this->validationErrors['type'] = __('Per inviare questa Email in formato Testo, il campo Testo della Email non può essere vuoto.');
					return false;
				}
			break;
			case self::$BOTH:
				if(empty($mail['Mail']['html']) || empty($mail['Mail']['text'])) {
					$this->validationErrors['type'] = __('Per inviare questa Email sia in formato HTML, sia in formato Testo, i campi HTML e Testo non possono essere vuoti.');
					return false;
				}
			break;
		}
		
		
		if(empty($data['Mailinglist']['Mailinglist'])) {
			$this->Mailinglist->validationErrors['Mailinglist'] = __('Devi selezionare almeno una lista di destinatari');
			return false;
		}
		
		if(!$this->Smtp->exists($data['Sending']['smtp_id'])) {
			$this->validationErrors['smtp_id'] = __('Devi selezionare un indirizzo di invio');
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
		
		$data['Recipient'] = $this->__extractRecipients(
			$data['Mailinglist']['Mailinglist'], 
			isset($data['conditions']) && $data['Sending']['enable_conditions']?$data['conditions']:false,
			$data
		);
		unset($data['conditions']);
		
		if(empty($data['Recipient'])) {
			$this->Mailinglist->validationErrors['Mailinglist'] = __('Nessun destinatario trovato');
			return false;
		}
		
		$data['Sending']['recipient_count'] = count($data['Recipient']);
		unset($data['Mailinglist']['Mailinglist']);
		
		$data['Sending']['smtp_email'] = $this->Smtp->field('email', array('Smtp.id' => $data['Sending']['smtp_id']));
		$data['Sending']['status'] = 0;
		
		return $this->saveAssociated($data);
	}
	
	
	public function testConditions($data) {
	
		$recipients = $this->__extractRecipients(
			$data['Mailinglist']['Mailinglist'], 
			isset($data['conditions'])?$data['conditions']:false,
			$data
		);
		
		return count($recipients);
		
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
	
	
	private function __extractRecipients($mailinglists, $filter, $data) {
		$recipients = array();
		$return = array();
		if(!empty($mailinglists)) {
		
			if($filter) {
				$filter = $this->__prettifyFilter($filter);
			}
			
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
        					'type' => 'INNER',
        					'conditions' => array(
            					'Member.id = MailinglistsMember.member_id',
        					)
   						)
					),
					'conditions' => array(
						'MailinglistsMember.mailinglist_id' => $mailinglists
					),
					'fields' => array_merge(
						array(
							'DISTINCT Member.id',
							'Member.email',
							'Member.secret',
						),
						array_map(
							function($el) {
								return 'Member.'.$el;
							},
							$customFields
						)
					)
				)
			);
			
			if(!empty($recipients)) {
				$recipientsToFilter = Hash::extract($recipients, '{n}.Member.id');
				if($filter !== false) {
					$recipientsToFilter = $this->__filterRecipients($recipientsToFilter, $filter, $data);
				}
			}
			
			foreach($recipients as $recipient) {
				if(in_array($recipient['Member']['id'], $recipientsToFilter)) {
					$return[] = array(
						'member_id' => $recipient['Member']['id'],
						'member_email' => $recipient['Member']['email'],
						'member_secret' => $recipient['Member']['secret'],
						'member_data' => json_encode($recipient['Member']),
						'user_id' => AuthComponent::user('id')
					);
				}			
			}
			
		}
		$this->virtualFields = array();
		return $return;
	}
	
	
	private function __filterRecipients($recipients, $filter, $data, $op = 'and') {
		
		foreach($filter as $key => &$val) {
			if(in_array($key, array('and', 'or'))) {
				foreach($val as $subkey => &$condition) {
					if(isset($condition['value'])) {
						// ogni funzione ritorna un array di id
						switch($condition['value']) {
							case 'member_sice':
								if(!empty($condition['args']['from'])) {
									$from = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['from'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$from->setTime(0, 0, 0);
									$from->setTimezone(new DateTimeZone('UTC'));
									$from = $from->format('Y-m-d H:i:s');
								}
								else
									$from = false;
									
								if(!empty($condition['args']['to'])) {
									$to = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['to'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$to->setTime(23, 59, 59);
									$to->setTimezone(new DateTimeZone('UTC'));
									$to = $to->format('Y-m-d H:i:s');
								}
								else
									$to = false;
									
								$condition = $this->Recipient->Member->filterMemberSince(
									$recipients, 
									$condition['args']['list'], 
									$from, 
									$to
								);
								
							break;
							case 'unsubscribing':
									
								$from = !empty($condition['args']['from'])?$condition['args']['from']:false;
								$to = !empty($condition['args']['to'])?$condition['args']['to']:false;
								
								if(!empty($condition['args']['from_date'])) {
									$from_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['from_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$from_date->setTime(0, 0, 0);
									$from_date->setTimezone(new DateTimeZone('UTC'));
									$from_date = $from_date->format('Y-m-d H:i:s');
								}
								else
									$from_date = false;
									
								if(!empty($condition['args']['to_date'])) {
									$to_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['to_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$to_date->setTime(23, 59, 59);
									$to_date->setTimezone(new DateTimeZone('UTC'));
									$to_date = $to_date->format('Y-m-d H:i:s');
								}
								else
									$to_date = false;
								
								$condition = $this->Recipient->Member->filterUnsubscribing(
									$recipients, 
									$condition['args']['list'], 
									$from, 
									$to,
									$from_date,
									$to_date
								);
								
							break;
							case 'sendings':
							
								$from = !empty($condition['args']['from'])?$condition['args']['from']:false;
								$to = !empty($condition['args']['to'])?$condition['args']['to']:false;
								
								if(!empty($condition['args']['from_date'])) {
									$from_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['from_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$from_date->setTime(0, 0, 0);
									$from_date->setTimezone(new DateTimeZone('UTC'));
									$from_date = $from_date->format('Y-m-d H:i:s');
								}
								else
									$from_date = false;
									
								if(!empty($condition['args']['to_date'])) {
									$to_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['to_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$to_date->setTime(23, 59, 59);
									$to_date->setTimezone(new DateTimeZone('UTC'));
									$to_date = $to_date->format('Y-m-d H:i:s');
								}
								else
									$to_date = false;
								
								$condition = $this->Recipient->Member->filterSendings(
									$recipients, 
									$from, 
									$to,
									$from_date,
									$to_date
								);
								
							break;
							case 'opened':
							
								$from = !empty($condition['args']['from'])?$condition['args']['from']:false;
								$to = !empty($condition['args']['to'])?$condition['args']['to']:false;
								
								$type = $condition['args']['type'];
								
								if(!empty($condition['args']['from_date'])) {
									$from_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['from_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$from_date->setTime(0, 0, 0);
									$from_date->setTimezone(new DateTimeZone('UTC'));
									$from_date = $from_date->format('Y-m-d H:i:s');
								}
								else
									$from_date = false;
									
								if(!empty($condition['args']['to_date'])) {
									$to_date = DateTime::createFromFormat(
										'd/m/Y', 
										$condition['args']['to_date'], 
										new DateTimeZone(AuthComponent::user('timezone'))
									);
								
									$to_date->setTime(23, 59, 59);
									$to_date->setTimezone(new DateTimeZone('UTC'));
									$to_date = $to_date->format('Y-m-d H:i:s');
								}
								else
									$to_date = false;
								
								$condition = $this->Recipient->Member->filterOpened(
									$recipients, 
									$from, 
									$to,
									$from_date,
									$to_date,
									$type
								);
								
							break;
							default:
								if($op == 'and') {
									$condition = $recipients;	
								}
								else {
									unset($val[$subkey]);
								}
						}
					}
					else {
						if(!empty($condition)) {
							list($subkey, $subval) = each($condition);
							if(in_array($subkey, array('and', 'or'))) {
								$condition = $this->__filterRecipients($recipients, $condition, $data, $subkey);
							}
						}
					}
					
				}
			}
		}
		//prima di ritornare se l'operatore è or faccio un merge di tutti gli array altrimenti un intersect
		
		if($op == 'and') {
			$filter = count($filter[$op])>1?
				call_user_func_array('array_intersect', $filter[$op]):isset($filter[$op][0])?$filter[$op][0]:array();
		}
		else {
			$filter = count($filter[$op])>1?
				call_user_func_array('array_merge', $filter[$op]):isset($filter[$op][0])?$filter[$op][0]:array();
		}
		
		return $filter;
	}
	
	private function __prettifyFilter($filter, $operator = 'and') {
		$slices = array();
		foreach($filter as $condition) {
			if(in_array($condition['value'], array('and', 'or'))) {
				$slices[] = $this->__prettifyFilter($condition['subconditions'], $condition['value']);
			}
			else {
				$slices[] = $condition;
			}
		}
		return array($operator => $slices);
		
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
		
		$data = $this->find('first',array(
			'recursive' => -1,
			'conditions' => array('Sending.id' => $id),
			'fields' => array('Mail.user_id'),
			'contain' => array('Mail')
		));
		
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
	
	
	public function validateFilterConditions($conditions) {
	
		App::uses('Validation', 'Utility');
	
		if(is_array($conditions)) {
			foreach($conditions as &$condition) {
				if(isset($condition['value']) && !empty($condition['value'])) {
					if(in_array(strtolower($condition['value']), array('and', 'or'))) {
						if(isset($condition['subconditions']) && is_array($condition['subconditions'])) {
							if(!$this->validateFilterConditions($condition['subconditions'])) {
								return false;
							}
						}
						else {
							return false;
						}
					}
					else {
						switch(strtolower($condition['value'])) {
							case 'member_sice':
								if(
									isset($condition['args']['list']) && 
									(isset($condition['args']['from']) || isset($condition['args']['to']))
								) {
									if(!empty($condition['args']['list'])) {
										try{
											if(!$this->Mailinglist->checkPerm($condition['args']['list'], null, AuthComponent::user('id')))
												return false;
										}
										catch(Exception $e) {
											return false;
										}
									}
									else {
										return false;
									}

									if(!empty($condition['args']['from'])) {
										$from = DateTime::createFromFormat('d/m/Y', $condition['args']['from']);
										if(!$from instanceof DateTime) {
											return false;
										}
									}
									else {
										$from = false;
									}
									if(!empty($condition['args']['to'])) {
										$to = DateTime::createFromFormat('d/m/Y', $condition['args']['to']);
										if(!$to instanceof DateTime) {
											return false;
										}
									}
									else {
										$to = false;
									}
									if(($from == false && $to == false) || ($from != false && $to != false && $from > $to)) {
										return false;
									} 
								}
								else {
									return false;
								}
							break;
							case 'unsubscribing':
								if(
									isset($condition['args']['list']) && 
									(isset($condition['args']['from']) || isset($condition['args']['to'])) &&
									(isset($condition['args']['from_date']) || isset($condition['args']['to_date']))
								) {
								
									if(!empty($condition['args']['list'])) {
										try{
											if(!$this->Mailinglist->checkPerm($condition['args']['list'], null, AuthComponent::user('id')))
												return false;
										}
										catch(Exception $e) {
											return false;
										}
									}
									else {
										return false;
									}
								
									if(
										!empty($condition['args']['from']) && 
										(!Validation::naturalNumber($condition['args']['from'], true) || $condition['args']['from'] < 0)
									) {
										return false;
									}
									
									if(
										!empty($condition['args']['to']) && 
										(!Validation::naturalNumber($condition['args']['to']) || $condition['args']['to'] < 0)
									) {
										return false;
									}
									
									if(
										(empty($condition['args']['from']) && empty($condition['args']['to'])) || 
										(
											!empty($condition['args']['from']) && 
											!empty($condition['args']['to']) && 
											$condition['args']['from'] > $condition['args']['to']
										)
									) {
										return false;
									} 
									
									
									if(!empty($condition['args']['from_date'])) {
										$from_date = DateTime::createFromFormat('d/m/Y', $condition['args']['from_date']);
										if(!$from_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$from_date = false;
									}
									
									if(!empty($condition['args']['to_date'])) {
										$to_date = DateTime::createFromFormat('d/m/Y', $condition['args']['to_date']);
										if(!$to_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$to_date = false;
									}
									
									if(
										($from_date == false && $to_date == false) ||
										($from_date != false && $to_date != false && $from_date > $to_date)
									) {
										return false;
									}
									 
								}
								else {
									return false;
								}
							break;
							case 'sendings':
								if(
									(isset($condition['args']['from']) || isset($condition['args']['to'])) &&
									(isset($condition['args']['from_date']) || isset($condition['args']['to_date']))
								) {
								
									if(
										!empty($condition['args']['from']) && 
										(!Validation::naturalNumber($condition['args']['from'], true) || $condition['args']['from'] < 0)
									) {
										return false;
									}
									
									if(
										!empty($condition['args']['to']) && 
										(!Validation::naturalNumber($condition['args']['to']) || $condition['args']['to'] < 0)
									) {
										return false;
									}
									
									if(
										(empty($condition['args']['from']) && empty($condition['args']['to'])) || 
										(
											!empty($condition['args']['from']) && 
											!empty($condition['args']['to']) && 
											$condition['args']['from'] > $condition['args']['to']
										)
									) {
										return false;
									}
									
									
									if(!empty($condition['args']['from_date'])) {
										$from_date = DateTime::createFromFormat('d/m/Y', $condition['args']['from_date']);
										if(!$from_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$from_date = false;
									}
									
									if(!empty($condition['args']['to_date'])) {
										$to_date = DateTime::createFromFormat('d/m/Y', $condition['args']['to_date']);
										if(!$to_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$to_date = false;
									}
									
									if(
										($from_date == false && $to_date == false) ||
										($from_date != false && $to_date != false && $from_date > $to_date)
									) {
										return false;
									}
									
									 
								}
								else {
									return false;
								}
							break;
							case 'opened':
								if(
									isset($condition['args']['type']) && 
									(isset($condition['args']['from']) || isset($condition['args']['to'])) && 
									(isset($condition['args']['from_date']) || isset($condition['args']['to_date']))
								) {
									if(!empty($condition['args']['from'])) {
									 	if(
									 		!Validation::naturalNumber($condition['args']['from']) || 
									 		($condition['args']['type'] == 'perc' && !is_between($condition['args']['from'], 0, 100)) ||
									 		($condition['args']['type'] != 'perc' && $condition['args']['from'] < 0)
									 	) {
											return false;
										}
									}
									
									if(!empty($condition['args']['to'])) {
									 	if(
									 		!Validation::naturalNumber($condition['args']['to']) || 
									 		($condition['args']['type'] == 'perc' && !is_between($condition['args']['to'], 0, 100)) ||
									 		($condition['args']['type'] != 'perc' && $condition['args']['to'] < 0)
									 	) {
											return false;
										}
									}
									
									if(
										(empty($condition['args']['from']) && empty($condition['args']['to'])) || 
										(
											!empty($condition['args']['from']) && 
											!empty($condition['args']['to']) && 
											$condition['args']['from'] > $condition['args']['to']
										)
									) {
										return false;
									}
									
									
									if(!empty($condition['args']['from_date'])) {
										$from_date = DateTime::createFromFormat('d/m/Y', $condition['args']['from_date']);
										if(!$from_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$from_date = false;
									}
									
									if(!empty($condition['args']['to_date'])) {
										$to_date = DateTime::createFromFormat('d/m/Y', $condition['args']['to_date']);
										if(!$to_date instanceof DateTime) {
											return false;
										}
									}
									else {
										$to_date = false;
									}
									
									if(
										($from_date == false && $to_date == false) ||
										($from_date != false && $to_date != false && $from_date > $to_date)
									) {
										return false;
									}
								}
								else {
									return false;
								}
							break;
						}
					}
				}
			}
		}
		return true;
	}

}
