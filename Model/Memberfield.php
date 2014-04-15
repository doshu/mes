<?php
App::uses('AppModel', 'Model');


class Memberfield extends AppModel {

	public static $dataType = array(
		0 => 'varchar',
		1 => 'text',
		2 => 'boolean',
		3 => 'date'
	);
 
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'unique' => array(
				'rule' => array('isFieldUnique', 'name'),
				'message' => 'Esiste già un campo con questo nome',
			),
		),
		'code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'format' => array(
				'rule' => '/^[a-z_]*$/i',
				'message' => 'Questo campo può contenere solo lettere e _',
			),
			'unique' => array(
				'rule' => array('isFieldUnique', 'code'),
				'message' => 'Esiste già un campo con questo nome',
			),
			'restricted' => array(
				'rule' => array('isRestricted'),
				'message' => 'Questo nome non è utilizzabile',
			)
		),
		'type' => array(
			'validType' => array(
				'rule' => array('inList', array('0', '1', '2', '3')),
				'message' => 'Valore campo errato',
			)
		),
		'in_grid' => array(
			'validType' => array(
				'rule' => array('boolean'),
				'message' => 'Valore campo errato',
			)
		),
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
		)
	);

	
	public $hasMany = array(
		'Memberfieldvalue' => array(
			'className' => 'Memberfieldvalue',
			'foreignKey' => 'memberfield_id',
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


	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	public function isFieldUnique($check, $field) {
		
		$conditions = array(
			$field => $check[$field],
			'user_id' => AuthComponent::user('id')
		);
		
		if(isset($this->data['Memberfield']['id']) && $this->data['Memberfield']['id']) {
			$conditions['id <>'] = $this->data['Memberfield']['id'];
		}
		
		return !$this->find('count', array(
			'recursive' => -1,
			'conditions' => $conditions
		));
	}
	
	public function isRestricted($check) {
		return !in_array($check['code'], array_keys($this->Memberfieldvalue->Member->schema()));
	}
	
	public function checkPerm($id, $params, $userId) {
	
		$data = (bool)$this->find('count',array(
			'recursive' => -1,
			'conditions' => array('id' => $id, 'user_id' => $userId),
		));
		
		if($check)
			return true;
		throw new NotFoundException();
	}
	
	
	
}
