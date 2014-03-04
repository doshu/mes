<?php

	class GeoBehavior extends ModelBehavior {
	
		public function geoDataToChartData($Model, $data, $dataModel = 'Recipient') {
			$chartData = array();
	
			foreach($data as $line) {
				$coord = array($line[$dataModel]['lat'], $line[$dataModel]['lon']);
				$name = array();
				if(!empty($line[$dataModel]['country'])) {
					$name[] = $line[$dataModel]['country'];
				}
				if(!empty($line[$dataModel]['region'])) {
					$name[] = $line[$dataModel]['region'];
				}
				$name = implode(', ', $name);
				$obj = new StdClass();
				$obj->latLng = $coord;
				$obj->name = $name;
				$chartData[] = $obj;
			}
		
			return $chartData;
		}
	
	}

?>
