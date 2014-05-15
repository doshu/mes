<?php

	class HabtmCreatedBehavior extends ModelBehavior {
	
		public function afterSave(Model $model, $created, $options = array()) {
			foreach($model->getAssociated() as $association => $type) {
				if(strtolower($type) == 'hasandbelongstomany') {
					if(isset($model->data[$association][$association]) && isset($model->hasAndBelongsToMany[$association])) {
						
						$joinModel = Inflector::classify($model->hasAndBelongsToMany[$association]['joinTable']);
						$foreignKey = $model->hasAndBelongsToMany[$association]['foreignKey'];
						$associationForeignKey = $model->hasAndBelongsToMany[$association]['associationForeignKey'];
						if(
							$model->{$joinModel}->updateAll(
								array($joinModel.'.created' => "'".date('Y-d-m H:i:s')."'"), 
								array(
									$joinModel.'.created' => null,
									$joinModel.'.'.$foreignKey => $model->data[$model->alias][$model->primaryKey],
									$joinModel.'.'.$associationForeignKey => $model->data[$association][$association],
								)
							)
						)
							return true;
						return false;
					}
				}
			}
			
			return true;
		}
	
	}

?>
