<?php
App::uses('AppModel', 'Model');


class Link extends AppModel {


	public $validate = array(
		'recipient_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);


	public $belongsTo = array(
		'Recipient' => array(
			'className' => 'Recipient',
			'foreignKey' => 'recipient_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	
	
}
