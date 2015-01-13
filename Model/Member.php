<?php
App::uses('AppModel', 'Model');


class Member extends AppModel {

	
	public $actsAs = array(
		'Containable', 
		'Eav' => array(
			'fieldModel' => 'Memberfield',
			'fieldValueModel' => 'Memberfieldvalue',
			'fieldValueForeignKey' => 'member_id',
			'fieldForeignKey' => 'memberfield_id'
		),
		'HabtmCreated'
	);	
	
	const isValid = 1;
	const isNotValid = 0;
	const cannotValidate = 2;
	
	
	public function __construct($id = false, $table = null, $ds = null) {
		$this->actsAs['Eav']['fieldScope'] = array('Memberfield.user_id' => AuthComponent::user('id'));
		parent::__construct($id, $table, $ds);
	}
 
	public $validate = array(
		'email' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'email' => array(
				'rule' => array('email'),
				'message' => 'Inserisci una email valida',
			),
		),
		'secret' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
		),
	);


	public $hasMany = array(
		'Recipient' => array(
			'className' => 'Recipient',
			'foreignKey' => 'member_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Memberfieldvalue' => array(
			'className' => 'Memberfieldvalue',
			'foreignKey' => 'member_id',
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
			'foreignKey' => 'member_id',
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


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Mailinglist' => array(
			'className' => 'Mailinglist',
			'joinTable' => 'mailinglists_members',
			'foreignKey' => 'member_id',
			'associationForeignKey' => 'mailinglist_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => '',
			'with' => 'MailinglistsMember'
		)
	);
	
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	public function validateOnCreate($data) {
		
		$member = $this->getByEmailForCurrentUser($data['Member']['email']);
		if(!empty($member['Member']['id'])) {
			$this->validationErrors['email'] = __('Questo indirizzo è già utilizzato da un altro membro');
			return false;
		}
		
		if(isset($data['Mailinglist']['Mailinglist']) && is_array($data['Mailinglist']['Mailinglist'])) {
			foreach($data['Mailinglist']['Mailinglist'] as $mailinglist) {
				if(
					!$this->Mailinglist->exists($mailinglist) || 
					!$this->Mailinglist->checkPerm($mailinglist, array(), AuthComponent::user('id'))
				)
				{
					return false;
				}
			}
		}
		return true;
	}
	
	
	public function validateOnEdit($data) {
		
		$member = $this->getByEmailForCurrentUser($data['Member']['email']);
		if(!empty($member['Member']['id']) && $member['Member']['id'] != $data['Member']['id']) {
			$this->validationErrors['email'] = __('Questo indirizzo è già utilizzato da un altro membro');
			return false;
		}
		
		if(isset($data['Mailinglist']['Mailinglist']) && is_array($data['Mailinglist']['Mailinglist'])) {
			foreach($data['Mailinglist']['Mailinglist'] as $mailinglist) {
				if(
					!$this->Mailinglist->exists($mailinglist) || 
					!$this->Mailinglist->checkPerm($mailinglist, array(), AuthComponent::user('id'))
				) 
				{
					return false;
				}
			}
		}
		return true;
	}
	
	
	
	public function __generateNewSecret($data) {
		Security::setHash('blowfish');
		return Security::hash($data);
	}
	
	public function isUserUnique($data) {
		return (bool) !$this->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array(
					'email' => $data['email']
				)
			)
		);
	}
	
	/*
	 * $type = 0 email+mailinglist, 1 solo email
	 */
	public function getImportExample() {
		$fp = fopen('php://memory', 'w');
		
		$csv = array();

		$csv = array(
			array(
				"email", 
				"list",
				__('codice_campo_personalizzato')
			),
			array('email1@example.com', 'mailinglist1', __('valore_campo_personalizzato')),
			array('email2@example.com', "mailinglist1\nmailinglist2\nmailinglist3"),
			array('email3@example.com', 'mailinglist4'),
		);
		
		
		foreach($csv as $line)
			fputcsv($fp, $line);
			
		fseek($fp, 0);
		$c = stream_get_contents($fp);
		fclose($fp);
		
		return $c;
	}
	
	
	public function importFile($file, $importExists) {
	
		$memberModelField = array('email', 'list');
		$customFields = $this->getModelFields();
		$status = true;
		$errors = array();		
		
		try {
			$fp = fopen($file, 'r');
			if(!$fp) {
			
				$errors[] = __('Impossibile importare il file');
				throw new Exception();
			}
			
			$header = fgetcsv($fp); //skip the first line include headers
			
			while($data = fgetcsv($fp)) {
				$data = array_combine($header, $data);
				$data['list'] = isset($data['list'])?array_filter(explode("\n", $data['list'])):array();
				
				if(!$this->emailExistsForCurrentUser($data['email'])) {
					$this->create();
					
					$mailinglists = array();
					foreach($data['list'] as $list) {
						$tmpM = $this->Mailinglist->getByNameForCurrentUser($list, array('id'));
						if(!empty($tmpM['Mailinglist']['id']))
							$mailinglists[] = $tmpM['Mailinglist']['id'];
					}
					$memberfieldvalues = array();
					foreach($customFields as $customField) {
						if(isset($data[$customField['Memberfield']['code']])) {
							$tmpFv['memberfield_id'] = $customField['Memberfield']['id'];
							$type = Memberfield::$dataType[$customField['Memberfield']['type']];
							$tmpFv['value_'.$type] = $data[$customField['Memberfield']['code']];
							$memberfieldvalues[] = $tmpFv;
						}
					}
					
					$secret = $this->__generateNewSecret($data['email'].time());

					$save = $this->saveAssociated(
						array(
							'Member' => array('email' => $data['email'], 'secret' => $secret, 'user_id' => AuthComponent::user('id')),
							'Mailinglist' => array(
								'Mailinglist' => $mailinglists
							),
							'Memberfieldvalue' => $memberfieldvalues
						),
						array('atomic' => false)
					);
				}
				elseif($importExists) {
				
					App::uses('MailinglistsMember', 'Model');
					$MailinglistsMember = new MailinglistsMember();
					$member = $this->getByEmailForCurrentUser($data['email'], array('id'));
					
					foreach($data['list'] as $list) {
						$tmpM = $this->Mailinglist->getByNameForCurrentUser($list);
						if(!empty($tmpM) && !$this->memberHasMailinglist($member['Member']['id'], $tmpM['Mailinglist']['id'])) {
							$MailinglistsMember->create();
							$save = $MailinglistsMember->save(
								array(
									'member_id' => $member['Member']['id'],
									'mailinglist_id' => $tmpM['Mailinglist']['id']
								)
							);
							if(!$save) {
								$errors[] = __("Si sono verificati errori durante l'importazione. L'operazione è stata annullata");
								throw new Exception();
							}
						}
					}
					
					unset($data['email']);
					unset($data['list']);
					
					foreach($customFields as $customField) {
						if(isset($data[$customField['Memberfield']['code']])) {
							$customFieldValue = $this->Memberfieldvalue->getFieldForMember(
								$customField['Memberfield']['id'], 
								$member['Member']['id']
							);
							
							$type = Memberfield::$dataType[$customField['Memberfield']['type']];
							$customFieldValue['Memberfieldvalue']['value_'.$type] = $data[$customField['Memberfield']['code']];
							$customFieldValue['Memberfieldvalue']['member_id'] = $member['Member']['id'];
							$customFieldValue['Memberfieldvalue']['memberfield_id'] = $customField['Memberfield']['id'];
							
							if(!isset($customFieldValue['Memberfieldvalue']['id'])) {
								$this->Memberfieldvalue->create();
							}
							$save = $this->Memberfieldvalue->save($customFieldValue);
							
							if(!$save) {
								$errors[] = __("Si sono verificati errori durante l'importazione. L'operazione è stata annullata");
								throw new Exception();
							}
						}
					}
					
				}
			}
		}
		catch(Exception $e) {
			return array(
				'status' => false,
				'errors' => $errors
			); 
		}
		
		return array(
			'status' => $status,
			'errors' => $errors
		);
	}
	
	
	public function validateImportFile($file, $importExists) {
		$error = array();
		switch($file['error']) {
			case UPLOAD_ERR_INI_SIZE:
				$error[] = __('Dimensione file troppo grande');
				return $error;
			break;
			case UPLOAD_ERR_NO_FILE:
				$error[] = __('Nessun file caricato');
				return $error;
			break;
		}
		
		$fileinfo = pathinfo($file['name']);
		if(strtolower($fileinfo['extension']) != 'csv') {
			$error[] = __('Estensione file non valida');
			return $error;
		}
		
		$valid = $this->__validateCsvEntry($file['tmp_name'], $importExists);
		if(!$valid['status'])
			$error = array_merge($error, $valid['errors']);
		
		return $error;
	}
	
	
	private function __validateCsvEntry($file, $importExists) {
	
		App::uses('Validation', 'Utility');
		$fp = fopen($file, 'r');
		$errors = array();
		$status = true;
			
		try {
		
			if(!$fp) {
				$errors[] = __('Impossibile importare il file');
				throw new Exception();
			}
			
			$header = fgetcsv($fp); //get the first line include headers
			$line = 1;
			
			
			if(!in_array('email', $header)) {
				$errors[] = __('Impossibile trovare il campo \'email\'', $line);
				throw new Exception();
			}
		
			while($data = fgetcsv($fp)) {
		
				$data = array_combine($header, $data);
				$data['list'] = isset($data['list'])?array_filter(explode("\n", $data['list'])):array();
			
				if(empty($data['email']) || !Validation::email($data['email'])) {
					$errors[] = __('Email non valida alla riga %s', $line);
					throw new Exception();
				}
				if(!$importExists && $this->emailExistsForCurrentUser($data['email'])) {
					$errors[] = __('Email %s già esistente alla riga %s', $data['email'], $line);
					throw new Exception();
				}
			
				foreach($data['list'] as $list) {
					if(!$this->Mailinglist->mailinglistExistsByNameForCurrentUser($list)) {
						$errors[] = __('Lista %s non esiste alla riga %s', $list, $line);
						throw new Exception();
					}
				}
			
				$line++;
			}
		
		}
		catch(Exception $e) {
			return array(
				'status' => false,
				'errors' => $errors
			); 
		}
		
		return array(
			'status' => $status,
			'errors' => $errors
		); 
	}
	
	
	
	public function emailExistsForCurrentUser($email) {
		return (bool)$this->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array(
					'email' => $email,
					'user_id' => AuthComponent::user('id')
				),
				'extra' => 'FOR UPDATE'
			)
		);
	}
	
	public function getByEmailForUser($email, $fields = array(), $recursive = -1, $user_id) {
		return $this->find(
			'first',
			array(
				'recursive' => $recursive,
				'conditions' => array(
					'email' => $email,
					'user_id' => $user_id
				),
				'fields' => $fields,
				//'extra' => 'FOR UPDATE'
			)
		);
	}
	
	public function getByEmailForCurrentUser($email, $fields = array(), $recursive = -1) {
		return $this->getByEmailForUser($email, $fields, $recursive, AuthComponent::user('id'));
	}
	
	
	public function getCsv($user_id, $list = null) {
	
		$conditions = array('Member.user_id' => $user_id);
		$join = array();
		if($list != null) {
			/*
			$db = $this->getDataSource();
			$subQuery = $db->buildStatement(
				array(
					'fields'     => array('`MailinglistsMember`.`member_id`'),
					'table'      => $db->fullTableName($this->MailinglistsMember),
					'alias'      => 'MailinglistsMember',
					'limit'      => null,
					'offset'     => null,
					'joins'      => array(),
					'conditions' => array('mailinglist_id' => $list),
					'order'      => null,
					'group'      => null
				),
				$this->MailinglistsMember
			);
			
			$subQuery = ' `Member`.`id` IN (' . $subQuery . ') ';
			$subQueryExpression = $db->expression($subQuery);
			$conditions[] = $subQueryExpression;
			*/
			$join[] = array(
				'table' => 'mailinglists_members',
				'alias' => 'MailinglistsMember',
				'conditions' => array('Member.id = MailinglistsMember.member_id AND MailinglistsMember.mailinglist_id = '.$list),
				'type' => 'INNER'
			);
		}
		
		$members = $this->find('all', array(
			'conditions' => $conditions,
			'recursive' => -1,
			'joins' => $join,
			'contain' => array('Mailinglist'),
		));
		
		
		$output = fopen('php://output', 'w');
 		ob_start();
 		$customFields = $this->getModelFields();
 		$headers = array('email', 'list');
 		foreach($customFields as $customField) {
 			$headers[] = $customField['Memberfield']['code'];
 		}
 		
 		fputcsv($output, $headers);
 		
 		foreach($members as $member) {
 			$data = array($member['Member']['email']);
 			
 			$data[] = !empty($member['Mailinglist'])?implode("\n", Hash::extract($member['Mailinglist'], '{n}.name')):'';
 			
 			foreach($customFields as $customField) {
 				$data[] = isset($member['Member'][$customField['Memberfield']['code']])?
 					$member['Member'][$customField['Memberfield']['code']]:'';
 			}
 			
 			fputcsv($output, $data);
 			
 		}
 		fclose($output);
 		return ob_get_clean();
		
	}
	

	public function memberHasMailinglist($member_id, $mailinglist_id) {
		App::uses('MailinglistsMember', 'Model');
		$MailinglistsMember = new MailinglistsMember();
		return (bool)$MailinglistsMember->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array(
					'member_id' => $member_id,
					'mailinglist_id' => $mailinglist_id
				)
			)
		);
	}
	
	
	public function unsubscribeMember($recipient, $mailinglists) {
		
		$this->getDataSource()->begin();
		$deleted = array();
		
		try {
			foreach($mailinglists as $mailinglist) {
		
				$MailinglistsMember = $this->MailinglistsMember->find(
					'first',
					array(
						'recursive' => -1,
						'conditions' => array(
							'MailinglistsMember.member_id' => $recipient['Member']['id'],
							'MailinglistsMember.mailinglist_id' => $mailinglist
						)
					) 
				);
			
				if(!empty($MailinglistsMember)) {
			
					$this->MailinglistsMember->id = $MailinglistsMember['MailinglistsMember']['id'];
					if($this->MailinglistsMember->delete()) {
						$deleted[] = $mailinglist;
					}
					else
						throw new Exception('');
				}
			}
		
			if(!empty($deleted)) {
				$this->Unsubscription->create();
				if(!$this->Unsubscription->saveAssociated(array(
					'Unsubscription' => array(
						'member_id' => $recipient['Member']['id'],
						'member_email' => $recipient['Recipient']['member_email'],
						'sending_id' => $recipient['Sending']['id'],
						'recipient_id' => $recipient['Recipient']['id'],
					),
					'Mailinglist' => $deleted
				))) {
					throw new Exception('');
				}
			}
			
			$this->getDataSource()->commit();
			return true;
		}
		catch(Exception $e) {
			$this->getDataSource()->rollback();
			return false;
		}
	}
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if(!$this->delete($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcuni Membri.'));
			}
		}
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function bulkUnsubscribe($ids, $list_id) {
		if(!$this->MailinglistsMember->deleteAll(
			array('MailinglistsMember.member_id' => $ids, 'MailinglistsMember.mailinglist_id' => $list_id)
		)) {
			$dbo->rollback();
			return array(false, __('Errore durante l\'operazione. Impossibile disiscrivere alcuni Membri.'));
		}
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
		$this->Behaviors->disable('Eav');
		$check = (bool)$this->find('count', array(
			'recursive' => -1, 
			'conditions' => array('id' => $id, 'user_id' => $userId)
		));
		$this->Behaviors->enable('Eav');
		if($check)
			return true;
		throw new NotFoundException();
	}
	
	
	public function filterMemberSince($recipients, $mailinglists, $min, $max) {
		$conditions = array();
		$conditions['member_id'] = $recipients;
		$conditions['mailinglist_id'] = $mailinglists;
		if($min !== false)
			$conditions['created >='] = $min;
		if($max !== false)
			$conditions['created <='] = $max;
		$result = $this->MailinglistsMember->find('list', array(
			'recursive' => -1,
			'fields' => array('id', 'id'),
			'conditions' => $conditions
		));
		return array_values($result);
	}
	
	
	public function filterUnsubscribing($recipients, $mailinglists, $min, $max, $from, $to) {
		$conditions = array();
		$conditions['Unsubscription.member_id'] = $recipients;
		$conditions['MailinglistsSending.mailinglist_id'] = $mailinglists;
		if($from)
			$conditions['Unsubscription.created >='] = $from;
		if($to)
			$conditions['Unsubscription.created <='] = $to;
		
		$this->Unsubscription->virtualFields['times'] = 'COUNT(*)';
		$result = $this->Unsubscription->find('list', array(
			'recursive' => -1,
			'fields' => array('member_id', 'times'),
			'conditions' => $conditions,
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('Unsubscription.sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mailinglists_sendings',
					'alias' => 'MailinglistsSending',
					'conditions' => array('Sending.id = MailinglistsSending.sending_id'),
					'type' => 'INNER'
				)
			),
			'group' => array('member_id')
		));
		
		unset($this->Unsubscription->virtualFields['times']);
		
		foreach($result as $recipient => $times) {
			if(($min !== false && $times < $min) || ($max !== false && $times >  $max))
				unset($result[$recipient]);
		}
		
		return array_keys($result);
	}
	
	
	
	public function filterSendings($recipients, $min, $max, $from, $to) {
		$conditions = array();
		$conditions['member_id'] = $recipients;
		if($from)
			$conditions['sended_time >='] = strtotime($from);
		if($to)
			$conditions['sended_time <='] = strtotime($to);
		
		$this->Recipient->virtualFields['times'] = 'COUNT(*)';
		$result = $this->Recipient->find('list', array(
			'recursive' => -1,
			'fields' => array('member_id', 'times'),
			'conditions' => $conditions,
			'group' => array('member_id')
		));
		
		unset($this->Recipient->virtualFields['times']);
		
		foreach($result as $recipient => $times) {
			if(($min !== false && $times < $min) || ($max !== false && $times >  $max))
				unset($result[$recipient]);
		}
		
		return array_keys($result);
	}
	
	
	
	public function filterOpened($recipients, $min, $max, $from, $to, $type) {
		$conditions = array();
		$conditions['member_id'] = $recipients;
		if($from)
			$conditions['sended_time >='] = strtotime($from);
		if($to)
			$conditions['sended_time <='] = strtotime($to);
		
		if($type == 'perc') {
			$this->Recipient->virtualFields['opened_times'] = 'COUNT(opened_time)';
			$this->Recipient->virtualFields['all_times'] = 'COUNT(*)';
			$result = array();
			$tmpResult = $this->Recipient->find('all', array(
				'recursive' => -1,
				'fields' => array('member_id', 'opened_times', 'all_times'),
				'conditions' => $conditions,
				'group' => array('member_id')
			));
			
			
			unset($this->Recipient->virtualFields['opened_times']);
			unset($this->Recipient->virtualFields['all_times']);
			
			foreach($tmpResult as $recipient) {
				if(
					($min == false || $recipient['Recipient']['opened_times'] >= $recipient['Recipient']['all_times']/100*$min) &&
					($max == false || $recipient['Recipient']['opened_times'] <= $recipient['Recipient']['all_times']/100*$max)
				) {
					$result[$recipient['Recipient']['member_id']] = true;
				}
			}
		}
		else {
			$this->Recipient->virtualFields['times'] = 'COUNT(*)';
			$result = $this->Recipient->find('list', array(
				'recursive' => -1,
				'fields' => array('member_id', 'times'),
				'conditions' => $conditions,
				'group' => array('member_id')
			));
		
			unset($this->Recipient->virtualFields['times']);
		
			foreach($result as $recipient => $times) {
				if(($min !== false && $times < $min) || ($max !== false && $times >  $max))
					unset($result[$recipient]);
			}
		}
		
		return array_keys($result);
	}
	
	
	
	public function filterMemberValid($recipients, $status) {
		$conditions = array();
		$this->Behaviors->disable('Eav');
		$conditions['id'] = $recipients;
		$conditions['valid'] = $status;
		$result = $this->find('list', array(
			'recursive' => -1,
			'fields' => array('id', 'id'),
			'conditions' => $conditions
		));
		$this->Behaviors->enable('Eav');
		return array_values($result);
	}
	
}
