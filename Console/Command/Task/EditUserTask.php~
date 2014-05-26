<?php

	class EditUserTask extends AppShell {
	
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
			
			
			$name = ucfirst($this->in('Insert the name', null, $user['User']['name']));
			$surname = ucfirst($this->in('Insert the surname', null, $user['User']['surname']));
			$password = $this->in('Insert the password (leave empty to not change)', null, null);
			$timezone = $this->in('Insert the timezone', null, $user['User']['timezone']);
			$filemanager_quota = $this->in('Insert the file manager quota MB', null, $user['User']['filemanager_quota']);
			$active = strtolower($this->in('User is Active?', array('y', 'n'), 'y'));
			$active = $active == '?'?1:0;
			
			$newUser = compact('name', 'surname', 'timezone', 'filemanager_quota', 'active');
			$newUser['id'] = $user['User']['id'];
			if(!empty($password)) {
				$newUser['password'] = $password;
			}
			
			$this->nl();
			$this->out('<info>name: '.$name.'</info>');
			$this->out('<info>surename: '.$name.'</info>');
			if(!empty($password)) {
				$this->out('<info>password: '.$password.'</info>');
			}
			$this->out('<info>timezone: '.$timezone.'</info>');
			$this->out('<info>filemanager quota: '.$filemanager_quota.'</info>');
			$this->out('<info>active: '.$active.'</info>');
			$this->nl();
			
			$commit = strtolower($this->in('Commit the changes?', array('y', 'n'), 'n'));
			if($commit == 'y') {
				if(!$this->User->save($newUser))
					$this->printValidationErrors($this->User);
				else
					$this->out('<info>User edited succesfully</info>');
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
