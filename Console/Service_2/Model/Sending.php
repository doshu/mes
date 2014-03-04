<?php

	require_once "Model.php";

	class Sending extends Model {
	
		public $table = 'sendings';
		public static $WAITING = 0;
		public static $SENDING = 1;
		public static $COMPLETED = 2;
		public static $ABORTED = 3;
		
		public static $HTML = 0;
		public static $TEXT = 1;
		public static $BOTH = 2;
		
		public function countToSend() {
			
			return $this->find('count', array(
				'where' => array(
					'status' => self::$WAITING,
					'OR' => array(
						array('time' => null),
						'UNIX_TIMESTAMP() >= time'
					)
				),
				//'for_update' => true
			));
		}
		
		public function getNextToSend() {
			$this->getDataSource()->beginTransaction();
			$ret =  $this->find('first', array(
				'fields' => array('id'),
				'where' => array(
					'status' => self::$WAITING,
					'OR' => array(
						array('time' => null),
						'UNIX_TIMESTAMP() >= time'
					)
				),
				'order' => array('id' => 'DESC'),
				'for_update' => true
			));
			$this->getDataSource()->commit();
			return $ret;
		}
		
		
		public function isToSend() {
			return ($this->data['status'] == self::$WAITING && (is_null($this->data['time']) || time() >= $this->data['time']));
		}
		
		public function setSendingStatus($id, $status) {
			return $this->save(array('id' => $id, 'status' => $status));
		}
	}

?>
