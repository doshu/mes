<?php
App::uses('AppModel', 'Model');

class Unsubscription extends AppModel {

	
	public $belongsTo = array(
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'sending_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Recipient' => array(
			'className' => 'Recipient',
			'foreignKey' => 'recipient_id',
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
		),
	);
	
	
	public $hasAndBelongsToMany = array(
		'Mailinglist' => array(
			'className' => 'Mailinglist',
			'joinTable' => 'mailinglists_unsubscriptions',
			'foreignKey' => 'unsubscription_id',
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
	
	
	public function checkPerm($id, $params, $userId) {
		$data = $this->read(null, $id);
		if(isset($data['Member']['user_id']) && !empty($data['Member']['user_id']))
			return $data['Member']['user_id'] == $userId;
		throw new NotFoundException();
	}
	
}
