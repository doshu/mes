<?php

	App::uses('AppHelper', 'View/Helper');

	class JavascriptHelper extends AppHelper {
	
		public $helpers = array('Html');
	
		public function setGlobal($vars = array()) {
			$globals = array();
			foreach($vars as $var => $val) {
				if(!is_numeric($val)) {
					$val = json_encode($val);
				}
				$globals[] = sprintf('var %s = %s;', $var, $val);
				
			}
			$globals = implode(' ',$globals);
			$this->Html->scriptBlock(
				$globals,
				array('inline' => false)
			);
		}
		
	}

?>
