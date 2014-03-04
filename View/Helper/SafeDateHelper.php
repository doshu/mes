<?php

	App::uses('AppHelper', 'View/Helper');

	class SafeDateHelper extends AppHelper {
	
		public static function setTimeZone($date, $timezone, $create = false) {
			$timezone = new DateTimeZone($timezone);
			if($create) {
				$newDate = clone $date;
				$newDate->setTimeZone($timezone);
				return $newDate;
			}
			
			$date->setTimeZone($timezone);
			return true;
		}
		
		public static function dateForUser($date, $format = 'd/m/Y H:i:s') {
			if($date instanceof DateTime) {
				$newDate = clone $date;
				$newDate->setTimeZone(new DateTimeZone(AuthComponent::user('timezone')));
				return $newDate->format($format);
			}
			return '';	
		}
		
	}

?>
