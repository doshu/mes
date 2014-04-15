<?php
App::uses('AppModel', 'Model');
App::uses('CakeEventListener', 'Event');

class Tempattachment extends AppModel {


	
	  public function ownedByUser($attachment, $user) {
	  	
	  	$check = (bool)$this->find('count',array(
			'recursive' => -1,
			'conditions' => array('id' => $id, 'user_id' => $user),
		));
		
	  	return $check;
	  }
    
}
