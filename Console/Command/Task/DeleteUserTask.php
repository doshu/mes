<?php

	class DeleteUserTask extends AppShell {
	
		public $uses = array('User');
		
		public function execute() {
		
			do {
				$username = $this->in('Insert the username (leave empty to exit)', null, null);
				if(empty($username))
					exit;
				$user = $this->User->find('first', array(
					'recursive' => -1,
					'conditions' => array('username' => $username)
				));
				if(empty($user)) {
					$this->out('<error>no user found</error>');
				}
			}while(empty($user));
			
			
			$this->nl();
			$this->out('<info>name: '.$user['User']['name'].'</info>');
			$this->out('<info>surename: '.$user['User']['surname'].'</info>');
			$this->out('<info>timezone: '.$user['User']['timezone'].'</info>');
			$this->out('<info>filemanager quota: '.$user['User']['filemanager_quota'].'</info>');
			$this->out('<info>active: '.$user['User']['active'].'</info>');
			$this->nl();
			
			$commit = strtolower($this->in('Delete the user?', array('y', 'n'), 'n'));
			if($commit == 'y') {
				$this->User->id = $user['User']['id'];
				if(!$this->User->delete())
					$this->out('<error>Error during deletion</error>');
				else
					$this->out('<info>User deleted succesfully</info>');
			}
		}
		
		
		public function printValidationErrors($Model) {
			foreach($Model->validationErrors as $field => $errors) {
				foreach($errors as $error) {
					$this->out('<error>'.$field.' -> '.$error.'</error>');
				}
			}
		}
	}

?>
