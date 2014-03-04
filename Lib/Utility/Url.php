<?php

	class Url {
		
		public static function isAbsolute($url) {
			$urlInfo = parse_url($url);
			return isset($urlInfo['scheme']) && isset($urlInfo['host']) && !empty($urlInfo['scheme']) && !empty($urlInfo['host']);
		}
		
		
		public static function appendParams($url, $params) {
			$parsed = explode('?', $url);
			if(count($parsed) > 1) {
				return $parsed[0].'?'.implode('&', $params).'&'.$parsed[1];
			}
			else return $parsed[0].'?'.implode('&', $params);
		}
	}

?>
