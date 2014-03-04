<?php
App::uses('AppModel', 'Model');


class Memberfieldvalue extends AppModel {


 
	public $validate = array(
		'member_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'memberfield_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'allowed' => array(
				'rule' => array('belongsToCurrentUser'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'value_date' => array(
			'rule' => array('date'),
				'message' => 'Questo campo non può essere vuoto',
				'allowEmpty' => true,
		)
	);

	
	public $belongsTo = array(
		'Memberfield' => array(
			'className' => 'Memberfield',
			'foreignKey' => 'memberfield_id',
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
	
	
	public function belongsToCurrentUser($check) {
		return $this->Memberfield->checkPerm($check['memberfield_id'], array(), AuthComponent::user('id'));
	}
	
	
	public function checkPerm($id, $params, $userId) {
		$data = $this->read(null, $id);
		if(isset($data['Member']['user_id']) && !empty($data['Member']['user_id']))
			return $data['Member']['user_id'] == $userId;
		throw new NotFoundException();
	}
	
	public function getFieldForMember($field, $member) {
		
		return $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'memberfield_id' => $field,
				'member_id' => $member
			)
		));
	}
	
	public function beforeSave($options = array()) {
		if(isset($this->data['Memberfieldvalue'])) {
			if(isset($this->data['Memberfieldvalue']['value_date']) && !empty($this->data['Memberfieldvalue']['value_date'])) {
				$newDate = DateTime::createFromFormat('d/m/Y', $this->data['Memberfieldvalue']['value_date']);
				if($newDate instanceof DateTime) {
					$this->data['Memberfieldvalue']['value_date'] = $newDate->format('Y-m-d');
				}
			}
		}
	}
	
}
