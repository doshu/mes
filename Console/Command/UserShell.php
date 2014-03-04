<?php

	class UserShell extends AppShell {
	
		public $uses = array('User');
		public $tasks = array('AddUser', 'EditUser', 'DeleteUser');
	
		public function main() {
		
			$this->out(__d('cake_console', 'Choose an Option'));
			$this->hr();
			$this->out('[A]dd User');
			$this->out('[E]dit User');
			$this->out('[D]elete User');
			$this->out('e[X]it');

			$action = strtoupper($this->in('What would you like to do?', array('A', 'E', 'D', 'X')));
			
			switch($action) {
				case 'A':
					$this->AddUser->execute();
				break;
				case 'E':
					$this->EditUser->execute();
				break;
				case 'D':
					$this->DeleteUser->execute();
				break;
				default:
					$this->out('<info>Exiting</info>');
			}
			
		}
		
		public function getOptionParser() {
			$parser = parent::getOptionParser();
			$parser->addSubcommand('add_user', array(
				'help' => 'Add an User',
				'parser' => $this->AddUser->getOptionParser(),
			))->addSubcommand('edit_user', array(
				'help' => 'Edit an User',
				'parser' => $this->EditUser->getOptionParser(),
			))->addSubcommand('delete_user', array(
				'help' => 'Delete an User',
				'parser' => $this->DeleteUser->getOptionParser(),
			));
			return $parser;
		}
	}

?>
