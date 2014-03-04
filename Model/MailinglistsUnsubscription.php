<?php
App::uses('AppModel', 'Model');

class MailinglistsSending extends AppModel {


	public $validate = array(
		'unsubscription_id' => array(
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
		'Unsubscription' => array(
			'className' => 'Unsubscription',
			'foreignKey' => 'unsubscription_id',
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
