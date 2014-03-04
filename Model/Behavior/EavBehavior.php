<?php

	class EavBehavior extends ModelBehavior {
	
		public function setup(Model $Model, $settings = array()) {
		
			if(!isset($settings['fieldModel']) || empty($settings['fieldModel'])) {
				$settings['fieldModel'] = get_class($Model).'field';
			}
			if(!isset($settings['fieldValueModel']) || empty($settings['fieldValueModel'])) {
				$settings['fieldValueModel'] = get_class($Model).'fieldvalue';
			}
			if(!isset($settings['fieldScope']) || empty($settings['fieldScope'])) {
				$settings['fieldScope'] = array();
			}
			if(!isset($settings['fieldValueForeignKey']) || empty($settings['fieldValueForeignKey'])) {
				$settings['fieldValueForeignKey'] = strtolower($Model->alias).'_id';
			}
			if(!isset($settings['fieldForeignKey']) || empty($settings['fieldForeignKey'])) {
				$settings['fieldForeignKey'] = strtolower($settings['fieldModel']).'_id';
			}
			$Model->eavSettings = $settings;
		} 
		
		public function beforeFind(Model $Model, $query) {
		
			
			$fields  = $this->getModelFields($Model);
			App::uses($Model->eavSettings['fieldValueModel'], 'Model');
			$fieldValueModel = new $Model->eavSettings['fieldValueModel'];
			$fieldModel = $Model->eavSettings['fieldModel'];
			$Model->eavVirtualFields = array();
		
			$joins = array();
			$joinFields = array();
			foreach($fields as $field) {
				$id = $field[$Model->eavSettings['fieldModel']]['id'];
				$code = $field[$Model->eavSettings['fieldModel']]['code'];
				$type = $fieldModel::$dataType[$field[$Model->eavSettings['fieldModel']]['type']];
				$table = $fieldValueModel->table;
				$alias = $fieldValueModel->alias.'__'.$code;
				$joins[] = array(
					'table' => $table,
					'alias' => $alias,
					'type' => 'LEFT',
					'conditions' => array(
	        			$Model->alias.'.id = '.$fieldValueModel->alias.'__'.$code.'.'.$Model->eavSettings['fieldValueForeignKey'],
	        			$fieldValueModel->alias.'__'.$code.'.'.$Model->eavSettings['fieldForeignKey'] => $id
	    			)
				);
				$Model->virtualFields[$code] = '`'.$alias.'`.`'.'value_'.$type;
				$Model->eavVirtualFields[] = $code;
				$joinFields[] = $code;
			}
		
			
			$query['joins'] = array_merge($query['joins'], $joins);
			if(empty($query['fields'])) {
				$query['fields'] = array_merge(array('*'), $joinFields);
			}
			//$query['recursive'] = -1;
			
			return $query;
		}
		
		
		public function afterFind(Model $Model, $results, $primary = false) {
			if(isset($Model->eavVirtualFields)) {
				foreach($Model->eavVirtualFields as $f) {
					if(isset($Model->virtualFields[$f])) {
						unset($Model->virtualFields[$f]);
					}
				}
			}
		}
		
		
		public function getModelFields($Model) {
			App::uses($Model->eavSettings['fieldModel'], 'Model');
			$fieldModel = new $Model->eavSettings['fieldModel'];
			$fields = $fieldModel->find('all', array(
				'recursive' => -1,
				'conditions' => $Model->eavSettings['fieldScope'],
				//'fields' => array('id', 'code', 'type')
			));
			
			return $fields;
		}
		
		
		public function isEavMethod($Model, $field) {
			App::uses($Model->eavSettings['fieldModel'], 'Model');
			$fieldModel = new $Model->eavSettings['fieldModel'];
			return (bool)$fieldModel->find('count', array(
				'recursive' => -1,
				'conditions' => array_merge(array('code' => $field), $Model->eavSettings['fieldScope']),
				//'fields' => array('id', 'code', 'type')
			));
		}
				
	}

?>
