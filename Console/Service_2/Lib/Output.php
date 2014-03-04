<?php

	/**
	 * Classe che gestisce l'output del programma
	 */

	class Output {
	
		private $_stream;
		public $eol = PHP_EOL;
		
		public $foreground = array(
			'black' => 30,
			'red' => 31,
			'green' => 32,
			'yellow' => 33,
			'blue' => 34,
			'magenta' => 35,
			'cyan' => 36,
			'white' => 37
		);
	
		public $background = array(
			'black' => 40,
			'red' => 41,
			'green' => 42,
			'yellow' => 43,
			'blue' => 44,
			'magenta' => 45,
			'cyan' => 46,
			'white' => 47
		);
	
		public $style = array(
			'bold' => 1,
			'underline' => 4,
			'blink' => 5,
			'reverse' => 7,
		);

		public $customStyle = array();
	
		public function __construct() {
			$this->_stream = fopen('php://stdout', 'w');
		}
		
		// scrive dell'output in accordo con lo stile
		public function write($text, $opt = array()) {
			$style = $this->_parseStyle($opt);
			if(!empty($style))
				fwrite($this->_stream, "\033[".implode(';', $style).'m'.$text."\033[0m");
			else
				fwrite($this->_stream, $text);
		}
		
		//esegue un write ma terminando la riga
		public function writeln($text, $opt = array()) {
			$this->write($text.$this->eol, $opt);
		}
		
		//crea l'output per lo stile
		private function _parseStyle($opt) {
			$style = array();
			
			if(is_string($opt) && isset($this->customStyle[$opt]))
				$opt = $this->customStyle[$opt];
				
			if(isset($opt['foreground']) && isset($this->foreground[$opt['foreground']]))
				$style[] = $this->foreground[$opt['foreground']];
			if(isset($opt['background']) && isset($this->background[$opt['background']]))
				$style[] = $this->background[$opt['background']];
			if(isset($opt['style'])) {
				$_styles = (array) $opt['style'];
				foreach($_styles as $_style) {
					if(isset($this->style[$_style]))
						$style[] = $this->style[$_style];
				}
			}
			return $style;
		}
		
		
		public function info($text) {
			$this->writeln('Info: '.$text, array('foreground' => 'blue'));
		}
		
		public function error($text) {
			$this->writeln('Error: '.$text, array('foreground' => 'red'));
		}
		
		public function warning($text) {
			$this->writeln('Warning: '.$text, array('foreground' => 'yellow'));
		}
	}

?>
