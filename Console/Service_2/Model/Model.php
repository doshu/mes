<?php

	require_once 'Config'.DS.'database.php';

	abstract class Model {
		
		public $table = '';
		public $primaryKey = 'id';
		public $dbConfig = 'default';
		
		private $__dataSource = null;
		private $__findType;
		
		public $data = array();
		public $id = null;
		public $sequence = null;
		
		public function __construct() {
			$databaseConfig = new DATABASE_CONFIG();
			$databaseConfig = $databaseConfig->{$this->dbConfig};
			require_once 'Model'.DS.'Datasource'.DS.$databaseConfig['datasource'].'.php';
			$adapter = explode('/', $databaseConfig['datasource']);
			$adapter = $adapter[count($adapter)-1].'Adapter';
			$this->__dataSource = new $adapter(
				$databaseConfig['host'], 
				$databaseConfig['database'], 
				$databaseConfig['login'], 
				$databaseConfig['password']
			);
			
			$this->__findType = array(
				'all' => '__findAll',
				'first' => '__findFirst',
				'count' => '__findCount'
			);
			
		}
		
		public function getDataSource() {
			return $this->__dataSource;
		}
		
		public function find($type, $opt = array()) {
			
			if(!array_key_exists($type, $this->__findType)) {
				throw new Exception('Find method not exists');
			}
			
			return $this->{$this->__findType[$type]}($opt);
		}
		
		private function __findAll($opt) {
			
			$opt['table'] = $this->table;
			
			return $this->__dataSource->select($opt);
		}
		
		private function __findFirst($opt) {
			$opt['table'] = $this->table;
			$opt['limit'] = 1;
			$result = $this->__dataSource->select($opt);
			return isset($result[0])?$result[0]:array();
		}
		
		private function __findCount($opt) {
			$opt['table'] = $this->table;
			$opt['fields'] = $this->__dataSource->getCountField();
			$result = $this->__dataSource->select($opt);
			
			if(isset($result[0][strtolower($this->__dataSource->getCountField())])) {
				return $result[0][strtolower($this->__dataSource->getCountField())];
			}
			return false;
		}
		
		public function load($id) {
			try {
				$this->id = $id;
				$this->data = $this->__findFirst(array(
					'table' => $this->table,
					'fields' => '*',
					'where' => array(
						$this->primaryKey => $this->id
					)	
				));
			}
			catch(Exception $e) {
				$this->clear();
				throw $e;
			}
			return $this;
		}
		
		public function clear() {
			$this->data = array();
			$this->id = null;
		}
		
		
		public function create($values) {
		
			if(empty($values)) {
				$values = $this->data;
			}
		
			$this->__dataSource->insert(array(
				'table' => $this->table,
				'values' => $values
			));
			return $this->__dataSource->getLastInsertId($this->sequence);
		}
		
		public function updateAll($set, $conditions) {
			$this->__dataSource->beginTransaction();
			try {
				$this->__dataSource->update(array(
					'table' => $this->table,
					'set' => $set,
					'where' => $conditions
				));
				$this->__dataSource->commit();
			}
			catch(Exception $e) {
				$this->__dataSource->rollback();
				throw $e;
			}
			
			return true;
		}
		
		
		public function save($values = array(), $checkExists = false) {
		
			if(empty($values)) {
				$values = $this->data;
			}
		
			if(isset($values[$this->primaryKey]) && !($checkExists && !$this->exists($values[$this->primaryKey]))) {
				$this->__dataSource->update(array(
					'table' => $this->table,
					'set' => $values,
					'where' => array($this->primaryKey => $values[$this->primaryKey])
				));
				return $values[$this->primaryKey];
			}
			else {
				$this->create($values);
				return $this->__dataSource->getLastInsertId($this->sequence);
			}
		}
		
		
		public function saveMany($records, $checkExists = false) {
			$this->__dataSource->beginTransaction();
			try {
				foreach($records as $record) { 
					if(!$this->save($record, $checkExists)) {
						$this->__dataSource->rollback();
						return false;
					}
				}
				$this->__dataSource->commit();
			}
			catch(Exception $e) {
				$this->__dataSource->rollback();
				throw $e;
			}
			
			return true;
		}
		
		
		public function delete($id = null) {
		
			if($id !== null) {
				$id = $this->id;
				$this->clear();
			}
			
			if($id !== null) {
				$this->__dataSource->delete(array(
					'table' => $this->table,
					'where' => array($this->primaryKey => $id),
					'limit' => 1
				));
				return true;
			}
			return false;
		}
		
		
		public function deleteAll($conditions) {
			$this->__dataSource->beginTransaction();
			try {
				$this->__dataSource->delete(array(
					'table' => $this->table,
					'where' => $conditions
				));
				$this->__dataSource->commit();
			}
			catch(Exception $e) {
				$this->__dataSource->rollback();
				throw $e;
			}
			
			return true;
		}
		
		
		public function exists($id) {
			if($this->find('count', array('where' => array($this->primaryKey => $id))))
				return true;
			return false;
		}
		
	}

?>
