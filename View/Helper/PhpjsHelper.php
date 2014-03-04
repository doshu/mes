<?php

	App::uses('AppHelper', 'View/Helper');

	class PhpjsHelper extends AppHelper {
	
		public $helpers = array('Html');
		private $__sdk = 'phpjs/';
		
		public function add($scripts, $inline = false) {
			if(!is_array($scripts))
				$scripts = array($scripts);
			foreach($scripts as $script) {
				$script = explode('/', $script);
				if(count($script) == 2) {
					list($package, $function) = $script;
					if($function == '*') {
						$function = $this->__getAllFunctions($package);
					}
					if(is_array($function)) {
						foreach($function as $f)
							$this->add($package.'/'.$f, $inline);
					}
					else
						$this->Html->script($this->__sdk.$package.'/'.$function, array('inline' => $inline));
				}
			}
		}
		
		private function __getAllFunctions($package) {
			return array_map(
				function($file) {
					return basename($file);
				},
				glob(WWW_ROOT.'js/'.$this->__sdk.$package.'/*.js', GLOB_NOSORT)
			);
		}
	}

?>
