<?php

	App::uses('Mysql', 'Model/Datasource/Database');

	class MysqlExtended extends Mysql {
	
		public function lockTableRW($tables) {
			$this->lockTable($tables, 'READ');
			$this->lockTable($tables, 'WRITE');
	  	}
	  	
	  	public function lockTable($tables, $type = 'READ') {
	  		$parsedTables = array();
	  		foreach($tables as $table => $alias) {
	  			if(is_int($table)) {
	  				$parsedTables[] = $alias;
	  			}
	  			else {
	  				$parsedTables[] = $table.' AS '.$alias;
	  			}
	  		}
			$this->execute('LOCK TABLES '.implode(' '.$type.', ', $parsedTables).' '.$type);
	  	}
	  	
	  	public function unlockTables() {;
			$this->execute('UNLOCK TABLES');
	  	}
	  	
	  	
	  	public function setIsolationLevel($level, $scope = '') {
	  		$this->query(sprintf('SET %s TRANSACTION ISOLATION LEVEL %s', $scope, $level));
	  	}
	  	
	}
	
	
?>
