<?php
App::uses('AppModel', 'Model');
/**
 * Mailinglist Model
 *
 * @property User $User
 * @property Member $Member
 */
class Mailinglist extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Member' => array(
			'className' => 'Member',
			'joinTable' => 'mailinglists_members',
			'foreignKey' => 'mailinglist_id',
			'associationForeignKey' => 'member_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Sending' => array(
			'className' => 'Sending',
			'joinTable' => 'mailinglists_sendings',
			'foreignKey' => 'mailinglist_id',
			'associationForeignKey' => 'sending_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'Unsubscription' => array(
			'className' => 'Unsubscription',
			'joinTable' => 'mailinglists_unsubscriptions',
			'foreignKey' => 'mailinglist_id',
			'associationForeignKey' => 'unsubscription_id',
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
	
	
	public function beforeSave($options = array()) {
		$this->data['Mailinglist']['user_id'] = AuthComponent::user('id');
		return true;
	}
	
	public function validateOnCreate($data) {
		if($this->mailinglistExistsByNameForCurrentUser($data['Mailinglist']['name'])) {
			$this->validationErrors['name'] = __("Questo nome è già utilizzato da un'altra lista.");
			return false;
		}
		return true;
	}
	
	public function validateOnEdit($data) {
		$mailinglist = $this->getByNameForCurrentUser($data['Mailinglist']['name'], array('id'));
		if(!empty($mailinglist) && $mailinglist['Mailinglist']['id'] != $data['Mailinglist']['id']) {
			$this->validationErrors['name'] = __("Questo nome è già utilizzato da un'altra lista.");
			return false;
		}
		return true;
	}
	
	public function getMembersCount($id) {
		App::uses('MailinglistsMember', 'Model');
		$MailinglistsMember = new MailinglistsMember();
		return $MailinglistsMember->find('count', array('recursive' => -1, 'conditions' => array('mailinglist_id' => $id)));
	}
	

	public function getAvg($user_id) {
		$all = $this->find('all', array('recursive' => -1, 'conditions' => array('user_id' => $user_id)));
		$members = 0;
		foreach($all as $mailinglist)
			$members += $this->getMembersCount($mailinglist['Mailinglist']['id']);
		if(count($all) > 0)
			return (int)($members/count($all));
		return 0;
	}
	
	
	public function mailinglistExistsByNameForCurrentUser($name) {
		return (bool)$this->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array(
					'name' => $name,
					'user_id' => AuthComponent::user('id')
				),
				'extra' => 'FOR UPDATE'
			)
		);
	}
	
	public function getByNameForCurrentUser($name, $fields = array(), $recursive = -1) {
		return $this->find(
			'first',
			array(
				'recursive' => $recursive,
				'conditions' => array(
					'name' => $name,
					'user_id' => AuthComponent::user('id')
				),
				'fields' => $fields
			)
		);
	}
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if(!$this->delete($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcune Liste.'));
			}
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
		$data = $this->read('user_id', $id);
		if(isset($data['Mailinglist']['user_id']) && !empty($data['Mailinglist']['user_id']))
			return $data['Mailinglist']['user_id'] == $userId;
		throw new NotFoundException();
	}
}
