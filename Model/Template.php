<?php
App::uses('AppModel', 'Model');


class Template extends AppModel {


	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
			'userUnique' => array(
				'rule' => array('userUnique'),
				'message' => 'Esiste già un email con questo nome',
			),
		),
		'code' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
			),
		)
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


	
	public function beforeSave($options = array()) {
		$this->data['Template']['user_id'] = AuthComponent::user('id');
		return true;
	}
	
	
	public function userUnique($data) {
		if(is_array($data)) {
			$data = array_values($data);
			$data = $data[0];
		}
		$conditions = array(
			'user_id' => AuthComponent::user('id'),
			'name' => $data
		);
		if(isset($this->data['Template']['id']) && !empty($this->data['Template']['id'])) {
			$conditions['id <>'] = $this->data['Template']['id'];
		}
		return !(bool)$this->find('count', array(
			'recursive' => -1,
			'conditions' => $conditions
		));
	}
	
	
	public function bulkDelete($ids) {
		$dbo = $this->getDataSource();
		$dbo->begin();
		foreach($ids as $id) {
			
			if(!$this->delete($id)) {
				$dbo->rollback();
				return array(false, __('Errore durante l\'operazione. Impossibile eliminare alcuni Template.'));
			}
			
		}
		
		$dbo->commit();
		return array(true, true);
	}
	
	
	public function checkPerm($id, $params, $userId) {
		
		$data = $this->find('first',array(
			'recursive' => -1,
			'conditions' => array('id' => $id),
			'fields' => array('user_id')
		));

		if(isset($data['Template']['user_id']) && !empty($data['Template']['user_id']))
			return $data['Template']['user_id'] == $userId;
		throw new NotFoundException();
	}

}
