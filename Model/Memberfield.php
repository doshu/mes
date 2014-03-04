<?php
App::uses('AppModel', 'Model');


class Memberfield extends AppModel {

	public static $dataType = array(
		0 => 'varchar',
		1 => 'text',
		2 => 'bool',
		3 => 'date'
	);
 
	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isFieldUnique', 'name'),
				'message' => 'Esiste già un campo con questo nome',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'format' => array(
				'rule' => '/^[a-z_]*$/i',
				'message' => 'Questo campo può contenere solo lettere e _',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => array('isFieldUnique', 'code'),
				'message' => 'Esiste già un campo con questo nome',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'in_grid' => array(
			'validType' => array(
				'rule' => array('boolean'),
				'message' => 'Valore campo errato',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			)
		),
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
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
		$data = $this->read('user_id', $id);
		if(isset($data['Memberfield']['user_id']) && !empty($data['Memberfield']['user_id']))
			return $data['Memberfield']['user_id'] == $userId;
		throw new NotFoundException();
	}
	
	
	
}
