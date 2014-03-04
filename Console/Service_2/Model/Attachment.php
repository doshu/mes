<?php

	require_once 'Model.php';

	class Attachment extends Model {
	
		public $table = 'attachments';
		
		public function getAttachmentsByMail($mail_id) {
		
			$out = array();
			
			$attachments = $this->find('all', array(
				'where' => array(
					'mail_id' => $mail_id
				)
			));
			
			if(count($attachments)) {
				foreach($attachments as $attachment) {
					$out[] = array(ATTACHMENT_DIR.$attachment['path'], $attachment['realname']);
				}
			}
			return $out;
		}
		
	}

?>
