<?php
App::uses('AppModel', 'Model');

class MailinglistsMember extends AppModel {


	public $validate = array(
		'member_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'mailinglist_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);


	public $belongsTo = array(
		'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'member_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Mailinglist' => array(
			'className' => 'Mailinglist',
			'foreignKey' => 'mailinglist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
}
