<?php

	require_once "Model.php";

	class Recipient extends Model {
	
		public $table = 'recipients';
		
		public function getRecipientBySending($sending_id) {
			return $this->find('all', array(
				'where' => array(
					'sending_id' => $sending_id,
					'sended' => 0
				)
			));
		}
		
	}

?>
