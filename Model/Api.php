<?php

App::uses('AppModel', 'Model');
App::uses('String', 'Utility');


class Api extends AppModel {


	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'userUnique' => array(
				'rule' => array('userUnique'),
				'message' => 'Esiste già un accesso API con questo nome',
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
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
	
	
	
	public function userUnique($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		
		$conditions = array(
			'user_id' => AuthComponent::user('id'),
			'name' => $data
		);
		
		if(isset($this->data['Api']['id']) && !empty($this->data['Api']['id'])) {
			$conditions['id <>'] = $this->data['Api']['id'];
		}
		
		return !(bool)$this->find('count', array(
			'recursive' => -1,
			'conditions' => $conditions
		));
	}
	
	
	
	public function sanitizeAcl($acl, $user_id) {
		$listsAcl = array();
		$membersAcl = array();
		if(isset($acl['lists']) && is_array($acl['lists'])) {
			foreach($acl['lists'] as $id => $settings) {
				if(is_int($id)) {
					try {
						if($this->User->Mailinglist->checkPerm($id, null, $user_id)) {
							$listsAcl[$id] = $settings;
						}
					}
					catch(Exception $e) {
					
					}
				}
			}
		}
		if(isset($acl['members']) && is_array($acl['members'])) {
			$membersAcl = $acl['members'];
		}
		
		return array(
			'members' => $membersAcl,
			'lists' => $listsAcl
		);
	}
	
	
	public function createClientKey() {
		do {
			$clientkey = str_replace('.', '', uniqid('', true));
		} while(
			$this->find('count', array('recursive' => -1, 'conditions' => array('clientkey' => $clientkey)))
		);
		return $clientkey;
	}
	
	
	public function createEncKey() {
		do {
			$enckey = String::uuid();
		} while(
			$this->find('count', array('recursive' => -1, 'conditions' => array('enckey' => $enckey)))
		);
		return $enckey;
	}
	
	
	public function checkPerm($id, $param, $userId) {
	
		$check = (bool)$this->find('count',array(
			'recursive' => -1,
			'conditions' => array('id' => $id, 'user_id' => $userId),
		));
		
		if($check)
			return true;
		throw new NotFoundException();
	}
}
