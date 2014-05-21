<?php

	class AddUserTask extends AppShell {
	
		public $uses = array('User');
		
		public function execute() {
		
			$name = ucfirst($this->in('Insert the name', null, null));
			$surname = ucfirst($this->in('Insert the surname', null, null));
			do {
				$username = $this->in('Insert the username', null, null);
			}while(empty($username));
			do {
				$password = $this->in('Insert the password', null, null);
			}while(empty($password));
			$timezone = $this->in('Insert the timezone', null, 'Europe/Rome');
			$filemanager_quota = $this->in('Insert the file manager quota MB', null, 500);
			$active = strtolower($this->in('User is Active?', array('y', 'n'), 'y'));
			$active = $active == 'y'?1:0;
			
			$newUser = compact('name', 'surname', 'username', 'password', 'timezone', 'filemanager_quota', 'active');
			
			if(!$this->User->save($newUser))
				$this->printValidationErrors($this->User);
			else
				$this->out('<info>User added succesfully</info>');
		
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
