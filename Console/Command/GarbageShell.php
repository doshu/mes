<?php

	App::uses('Daemon', 'Utility');
	App::uses('Folder', 'Utility');
	App::uses('File', 'Utility');

	class GarbageShell extends AppShell {
	
		public $uses = array('Tempattachment', 'Attachment');
		public $attachmentsDir;
		public $sandboxDir;
		public $sandboxTTL = 86400;//one day
	
		public function startup() {
		
		}
		
		public function initialize() {
			parent::initialize();
			Daemon::daemonize();
			Configure::load('path');
			$this->attachmentsDir = Configure::read('Path.Attachment');
			$this->sandboxDir = Configure::read('Path.Sandbox');
		}
	
		public function main() {
		    
		    
		    $attachmentsRecords = $this->Attachment->find('all', array(
		    	'recursive' => -1,
		    	'fields' => array('id', 'path')
		    ));
		    
		    $attachmentsDir = new Folder($this->attachmentsDir);
		    $attachmentsFiles = $attachmentsDir->find();
		    
		    /**
		     * if the file contained in the attachment record doesn't exists, the record will be deleted.
		     * if the file exists the value will be deleted from the attachment files array.
		     * the remaning value in the attachment files array are zombie attachment and will be deleted too.
		     */
		    foreach($attachmentsRecords as $attachment) {
		    	if($key = array_search($attachment['Attachment']['path'], $attachmentsFiles) === false) {
		    		$this->Attachment->id = $attachment['Attachment']['id'];
		    		$this->Attachment->delete();
		    		$this->Attachment->id = null;
		    	}
		    	else {
		    		unset($attachmentsFiles[$key]);
		    	}
		    }
		    
		    foreach($attachmentsFiles as $file) {
		    	unlink($this->attachmentsDir.$file);
		    }
		    
		    //------------------------------------------------------------------------
		    
		    $sandboxedRecords = $this->Tempattachment->find('all', array(
		    	'recursive' => -1,
		    	'fields' => array('id', 'tempname', 'created')
		    ));
		    
		    $sandboxDir = new Folder($this->sandboxDir);
		    $sandboxedFiles = $sandboxDir->find();
		    
		    /**
		     * the same for attachments. but if a sandox record exists sine 24 hours it will be deleted because it is a zombie 
		     */
		    foreach($sandboxedRecords as $sandboxed) {
		    	if(
		    		($key = array_search($sandboxed['Tempattachment']['tempname'], $sandboxedFiles) === false) ||
		    		(time() - strtotime($sandboxed['Tempattachment']['created']) >= $this->sandboxTTL)
		    	) {
		    		$this->Tempattachment->id = $sandboxed['Tempattachment']['id'];
		    		$this->Tempattachment->delete();
		    		$this->Tempattachment->id = null;
		    	}
		    	else {
		    		unset($attachmentsFiles[$key]);
		    	}
		    }
		    
		    foreach($attachmentsFiles as $file) {
		    	unlink($this->sandboxDir.$file);
		    }
		}
	}

?>
