<?php


App::uses('Model', 'Model');


class AppModel extends Model {
	
	public $actsAs = array('Containable');	
	
	public $cacheQueries = true;
	
	public function exists($id = null) {
		if ($id === null) {
			$id = $this->getID();
		}
		if ($id === false) {
			return false;
		}
		return (bool)$this->find('count', array(
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $id
			),
			'recursive' => -1,
			'callbacks' => false,
			'extra' => 'FOR UPDATE'
		));
	}
	
}
