<?php


	if($this->params['plugin']) {
		$css = APP.WEBROOT_DIR.DS.$this->params["plugin"].DS."css".DS.$this->params["controller"].DS.$this->params["action"].".css";
		$js = APP.WEBROOT_DIR.DS.$this->params["plugin"].DS."js".DS.$this->params["controller"].DS.$this->params["action"].".js";
		
		if(is_file($css) || is_link($css)){ 
			echo $this->Html->css(Inflector::camelize($this->params['plugin']).'.'.$this->params["controller"]."/".$this->params["action"]); 
		}
	
		if (is_file($js) || is_link($js)){ 
			echo $this->Html->script(Inflector::camelize($this->params['plugin']).'.'.$this->params["controller"]."/".$this->params["action"]); 
		}
	}
	else {
		$css = APP.WEBROOT_DIR.DS."css".DS.$this->params["controller"].DS.$this->params["action"].".css";
		$js = APP.WEBROOT_DIR.DS."js".DS.$this->params["controller"].DS.$this->params["action"].".js";
		
		if(is_file($css) || is_link($css)){ 
			echo $this->Html->css($this->params["controller"]."/".$this->params["action"]); 
		}
	
		if (is_file($js) || is_link($js)){ 
			echo $this->Html->script($this->params["controller"]."/".$this->params["action"]); 
		}
	}
	

	
?>
