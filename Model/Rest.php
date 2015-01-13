<?php

App::uses('AppModel', 'Model');
App::uses('Api', 'Model');

class Rest extends AppModel {

	public $currentData = null;
	public $useTable = false;
	
	/**
	 * controlla l'autenticità della richiesta.
	 * key deve essere uguale a hash_hmac('sha256', salt, enckey); prendendo salt e enckey partendo dalla clientkey
	 */
	public function authenticate($clientkey, $key) {
		$Api = ClassRegistry::init('Api');
		$apiKey = $Api->getByClientKey($clientkey);
		if(!empty($apiKey)) {
			if(hash_hmac('sha256', $apiKey['Api']['salt'], $apiKey['Api']['enckey']) == $key) {
				$this->currentData = $apiKey;
				return true;
			}
		}
		return false;
	}
	
	/**
	 * autorizza un client per una determinata azione
	 * @param action = azione da autorizzare
	 */
	public function authorize($actions, $type = 'or') {
		$type = strtolower($type);
		$acl = json_decode($this->currentData['Api']['acl'], true);
		$actions = (array)$actions;
		foreach($actions as $action) {
			if(Hash::check($acl, $action) && $config = Hash::get($acl, $action)) {
				if($config == 1) {
					if($type == 'or') {
						return true;
					}
				}
				else {
					if($type != 'or') {
						return false;
					}
				}
			}
			else {
				if($type != 'or') {
					return false;
				}
			}
		}
		return $type != 'or'
	}
}
