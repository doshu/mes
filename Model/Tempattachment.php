<?php
App::uses('AppModel', 'Model');
App::uses('CakeEventListener', 'Event');

class Tempattachment extends AppModel {


	
	  public function ownedByUser($attachment, $user) {
	  	
	  	$data = $this->find('first',array(
			'recursive' => -1,
			'conditions' => array('id' => $id),
			'fields' => array('user_id')
		));
		
	  	return $userId['Tempattachment']['user_id'] == $user;
	  }
    
}
