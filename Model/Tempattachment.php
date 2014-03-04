<?php
App::uses('AppModel', 'Model');
App::uses('CakeEventListener', 'Event');

class Tempattachment extends AppModel {


	
	  public function ownedByUser($attachment, $user) {
	  	
	  	$userId = $this->read('user_id', $attachment);
	  	return $userId['Tempattachment']['user_id'] == $user;
	  }
    
}
