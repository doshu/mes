<?php
App::uses('AppModel', 'Model');
/**
 * Mail Model
 *
 * @property User $User
 * @property Attachment $Attachment
 * @property Sending $Sending
 */
class Mail extends AppModel {

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
		'subject' => array(
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
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'mail_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Sending' => array(
			'className' => 'Sending',
			'foreignKey' => 'mail_id',
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
	
	public function beforeSave($options = array()) {
		$this->data['Mail']['user_id'] = AuthComponent::user('id');
		return true;
	}
	
	public function getUserMailId($user_id) {
		return $this->find(
			'list', 
			array(
				'recursive' => -1,
				'conditions' => array(
					'user_id' => $user_id
				),
				'fields' => array('id', 'id')
			)
		);
	}
	
	
	public function beforeDelete($cascade = true) {

		$id = $this->id;
		try {
			$this->Sending->deleteAll(array('Sending.mail_id'=> $id), true, true);
			return true;
		}
		catch(Exception $e) {
			return false;
		}
	}
	
	
	public function isInSending($id) {
		return (bool)$this->Sending->find(
			'count', 
			array(
				'recursive' => -1, 
				'conditions' => array(
					'mail_id' => $id, 
					'status' => array(Sending::$SENDING, Sending::$WAITING)
				)
			)
		);
	}
	
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			if($this->isInSending($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Alcune Email sono attualmente in corso di invio.'));
			}
			else {
				if(!$this->delete($id)) {
					$dbo->rollback();
					return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcune Email.'));
				}
			}
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
			
		$data = $this->read('user_id', $id);
		if(isset($data['Mail']['user_id']) && !empty($data['Mail']['user_id']))
			return $data['Mail']['user_id'] == $userId;
		throw new NotFoundException();
	}

}
