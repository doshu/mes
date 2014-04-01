<?php
App::uses('AppModel', 'Model');

class Mail extends AppModel {


	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'userUnique' => array(
				'rule' => array('userUnique'),
				'message' => 'Esiste già un email con questo nome',
			),
		),
		'subject' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
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


	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'mail_id',
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
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'mail_id',
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
	
	
	public function beforeSave($options = array()) {
		
		App::uses('Spamassassin', 'Network/Email');
		$Spamassassin = new Spamassassin();
		
		try {
			$html_spam_point = $Spamassassin->getSpamPoint($this->data['Mail']['html']);
			$text_spam_point = $Spamassassin->getSpamPoint($this->data['Mail']['text']);
			$html_spam_point['limit'] = max($html_spam_point['limit'], 1);
			$text_spam_point['limit'] = max($text_spam_point['limit'], 1);
			
			$this->data['Mail']['html_spam_point'] = 100*$html_spam_point['point']/$html_spam_point['limit'];
			$this->data['Mail']['text_spam_point'] = 100*$text_spam_point['point']/$text_spam_point['limit'];
		}
		catch(Exception $e) {
			$this->validationErrors['html'] = $this->validationErrors['text'] = $e->getMessage();
			return false;
		}
		
		$this->data['Mail']['user_id'] = AuthComponent::user('id');
		return true;
	}
	
	
	public function userUnique($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		$conditions = array(
			'user_id' => AuthComponent::user('id'),
			'name' => $data
		);
		if(isset($this->data['Mail']['id']) && !empty($this->data['Mail']['id'])) {
			$conditions['id <>'] = $this->data['Mail']['id'];
		}
		
		return !(bool)$this->find('count', array(
			'recursive' => -1,
			'conditions' => $conditions
		));
	}
	
	public function getUserMailId($user_id) {
		return $this->find(
			'list', 
			array(
				'recursive' => -1,
				'conditions' => array(
					'user_id' => $user_id
				),
				'fields' => array('id', 'id')
			)
		);
	}
	
	
	public function beforeDelete($cascade = true) {

		$id = $this->id;
		try {
			$this->Sending->deleteAll(array('Sending.mail_id'=> $id), true, true);
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}
	
	
	public function isInSending($id) {
		return (bool)$this->Sending->find(
			'count', 
			array(
				'recursive' => -1, 
				'conditions' => array(
					'mail_id' => $id, 
					'status' => array(Sending::$SENDING, Sending::$WAITING)
				)
			)
		);
	}
	
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if($this->isInSending($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Alcune Email sono attualmente in corso di invio.'));
			}
			else {
				if(!$this->delete($id)) {
					$dbo->rollback();
					return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcune Email.'));
				}
			}
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
			
		$data = $this->find('first',array(
			'recursive' => -1,
			'conditions' => array('id' => $id),
			'fields' => array('user_id')
		));
		
		if(isset($data['Mail']['user_id']) && !empty($data['Mail']['user_id']))
			return $data['Mail']['user_id'] == $userId;
		throw new NotFoundException();
	}

}
