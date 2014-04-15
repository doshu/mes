<?php
App::uses('AppModel', 'Model');


class Smtp extends AppModel {


	public $validate = array(
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Inserisci una email valida',

			),
		),
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',

			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
		),
		'host' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
		),
		'port' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Inserisci un numero di porta valido',
			),
		),
		'enctype' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'allowedChoice' => array(
				'rule'    => array('inList', array('none', 'ssl', 'tls')),
				'message' => 'Metodo di cifratura non valido'
			)
		),
		'authtype' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'allowedChoice' => array(
				'rule'    => array('inList', array('LOGIN', 'PLAIN', 'NTLM', 'CRAM-MD5')),
				'message' => 'Metodo di autenticazione non valido'
			)
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
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'smtp_id',
			'dependent' => false,
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
		$this->data['Smtp']['user_id'] = AuthComponent::user('id');
		return true;
	}
	
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if(!$this->delete($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcuni Indirizzi di Invio.'));
			}
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
		
		$check = (bool)$this->find('count',array(
			'recursive' => -1,
			'conditions' => array('id' => $id, 'user_id' => $userId),
		));
		
		if($check)
			return true;
		throw new NotFoundException();
	}

}
