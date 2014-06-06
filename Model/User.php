<?php
App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {


	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'This field cannot be empty'
			),
			'unique' => array(
				'rule' => array('isUnique'),
				'message' => 'Another user is using this username'
			),
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'This field cannot be empty'
			),
		),
	);


	public $hasMany = array(
		'Mailinglist' => array(
			'className' => 'Mailinglist',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Mail' => array(
			'className' => 'Mail',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Member' => array(
			'className' => 'Member',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Template' => array(
			'className' => 'Template',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Api' => array(
			'className' => 'Api',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
	
	
	public function beforeSave($options = array()) {
        if (isset($this->data['User']['password'])) {
        	$passwordHasher = new BlowfishPasswordHasher();
        	
            $this->data['User']['password'] = $passwordHasher->hash($this->data['User']['password']);
        }
        return true;
    }
    
    public function repeatPasswordValidation($check) {
		return $this->data['User']['newpwd'] == $this->data['User']['newpwd2'];
	}
	
	public function isUserCorrectPassword($pwd, $user) {
		if(is_array($pwd)) {
			$pwd = array_values($pwd);
        	$pwd = $pwd[0];
        }
        $pwd = AuthComponent::password($pwd);
        $user = $this->read('password', $user);
        $passwordHasher = new BlowfishPasswordHasher();
        return $passwordHasher->check($pwd, $user['User']['password']);
	}

}
