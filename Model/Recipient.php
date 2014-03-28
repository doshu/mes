<?php
App::uses('AppModel', 'Model');

class Recipient extends AppModel {

	public $actsAs = array('Geo');
	
	public $validate = array(
		'sending_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),

			),
		),
		'member_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);

	public $belongsTo = array(
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'sending_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'member_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	public $hasMany = array(
		'Link' => array(
			'className' => 'Link',
			'foreignKey' => 'recipient_id',
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
			'foreignKey' => 'recipient_id',
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
	
	
	public function checkPerm($id, $params, $userId) {
		
		$data = $this->find('first',array(
			'recursive' => -1,
			'conditions' => array('Recipient.id' => $id),
			'fields' => array('Member.user_id'),
			'contain' => array('Member')
		));
		
		if(isset($data['Member']['user_id']) && !empty($data['Member']['user_id']))
			return $data['Member']['user_id'] == $userId;
		throw new NotFoundException();
	}
	
	
	public function getToOpenBySecret($id, $secret) {
		
		return $this->find('first', array(
			'recursive' => -1,
			'fields' => array('id', 'opened', 'sending_id'),
			'conditions' => array(
				'id' => $id,
				'member_secret' => $secret
			)
		));	
	}


	public function getBrowserStats($user_id, $sending_id = null) {
		
		$this->Sending->Recipient->virtualFields['times'] = 'COUNT(browser)';
		$conditions = array('browser <>' => null, 'browser <>' => '');
		if(!is_null($sending_id)) {
			$conditions['sending_id'] = $sending_id;
		} 
		
		$result = $this->Sending->Recipient->find('all', array(
			'recursive' => -1,
			'fields' => array('browser', 'times'),
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				)
			),
			'group' => array('browser'),
			'conditions' => $conditions
		));
		$this->Sending->Recipient->virtualFields['times'] = array();
		return $result;
	}
	
	
	public function getDeviceStats($user_id, $sending_id = null) {
		
		$this->Sending->Recipient->virtualFields['times'] = 'COUNT(device)';
		$conditions = array('device <>' => null, 'device <>' => '');
		if(!is_null($sending_id)) {
			$conditions['sending_id'] = $sending_id;
		} 
		
		$result = $this->Sending->Recipient->find('all', array(
			'recursive' => -1,
			'fields' => array('device', 'times'),
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				)
			),
			'group' => array('device'),
			'conditions' => $conditions
		));
		$this->Sending->Recipient->virtualFields['times'] = array();
		return $result;
	}
	
	
	public function getOsStats($user_id, $sending_id = null) {
		
		$this->Sending->Recipient->virtualFields['times'] = 'COUNT(os)';
		$conditions = array('os <>' => null, 'os <>' => '');
		if(!is_null($sending_id)) {
			$conditions['sending_id'] = $sending_id;
		} 
		
		$result = $this->Sending->Recipient->find('all', array(
			'recursive' => -1,
			'fields' => array('os', 'times'),
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				)
			),
			'group' => array('os'),
			'conditions' => $conditions
		));
		$this->Sending->Recipient->virtualFields['times'] = array();
		return $result;
	}
	
	
	
	public function getUsageStats($user_id) {
	
		$sended = $this->Sending->Recipient->find('count', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				)
			),
			'conditions' => array('sended' => 1)
		));
		
		
		$opened = $this->Sending->Recipient->find('count', array(
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				)
			),
			'conditions' => array('sended' => 1, 'opened' => 1)
		));
		
		
		$followed = $this->Sending->Recipient->find('count', array(
			'recursive' => -1,
			'fields' => 'DISTINCT Recipient.id',
			'joins' => array(
				array(
					'table' => 'sendings',
					'alias' => 'Sending',
					'conditions' => array('sending_id = Sending.id'),
					'type' => 'INNER'
				),
				array(
					'table' => 'mails',
					'alias' => 'Mail',
					'conditions' => array('mail_id = Mail.id', 'Mail.user_id' => $user_id),
					'type' => 'INNER'
				),
				array(
					'table' => 'links',
					'alias' => 'Link',
					'conditions' => array('recipient_id = Recipient.id'),
					'type' => 'INNER'
				)
			),
			'conditions' => array('sended' => 1, 'opened' => 1)
		));
		
		return compact('sended', 'opened', 'followed');
		
	}
	
	
	public function getGeoStatsBySending($sending_id) {
		
		return $this->find('all', array(
			'fields' => array('lat', 'lon', 'country', 'region'),
			'recursive' => -1,
			'conditions' => array(
				'opened' => 1,
				'lat <>' => null,
				'lon <>' => null,
				'sending_id' => $sending_id
			)
		));
		
	}
	
	
	public function getGeoStatsByUser($user_id) {
		
		$recipients = $this->find('all', array(
			'fields' => array('lat', 'lon', 'country', 'region'),
			'recursive' => -1,
			'conditions' => array(
				'opened' => 1,
				'lat <>' => null,
				'lon <>' => null 
			),
			'joins' => array(
				array(
					'table' => 'members',
					'alias' => 'Member',
					'type' => 'inner',
					'conditions' => array(
						'Recipient.member_id = Member.id',
						'Member.user_id = '.$user_id
					)
				)
			)
		));
		
		
		return $recipients;
		
	}
}
