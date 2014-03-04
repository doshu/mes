<?php
App::uses('AppModel', 'Model');

class MailinglistsSending extends AppModel {


	public $validate = array(
		'sending_id' => array(
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
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'sending_id',
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
