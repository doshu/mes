<?php
App::uses('AppController', 'Controller');

class RecipientsController extends AppController {

	public function showSended($sending_id) {
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE',
			'opened' => '='
		);
		
		$this->paginate = array('Recipient' => array('recursive' => -1, 'order' => array('Recipient.member_email' =>  'desc')));
		
		$this->set(
			'sended',
			$this->paginate(
				'Recipient',
				array(
					'Recipient.sending_id' => $sending_id,
					'Recipient.sended' => 1
				)
			)
		);
		
		$this->set(
			'sendedCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
						'Recipient.sended' => 1
					)
				)
			)
		);
		
		$this->set(
			'allCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
					)
				)
			)
		);
		
		$this->set(
			'sending', 
			$this->Recipient->Sending->find(
				'first', 
				array(
					'conditions' => array('Sending.id' => $sending_id),
					'contain' => array('Mail'),
					'recursive' => -1
				)
			)
		);
	}
	
	
	public function showOpened($sending_id) {
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE',
			'opened_time' => array('BETWEEN', array('type' => 'datetime', 'convert' => true, 'format' => 'd/m/Y H:i:s'))
		);
		
		$this->paginate = array('Recipient' => array('contain' => array('Link'), 'recursive' => 2, 'order' => array('Recipient.opened_time' =>  'desc')));
		
		$this->set(
			'opened',
			$this->paginate(
				'Recipient',
				array(
					'Recipient.sending_id' => $sending_id,
					'Recipient.sended' => 1,
					'Recipient.opened' => 1
				)
			)
		);
		
		$this->set(
			'openedCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
						'Recipient.sended' => 1,
						'Recipient.opened' => 1
					)
				)
			)
		);
		
		$this->set(
			'allCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
					)
				)
			)
		);
		
		$this->set(
			'sending', 
			$this->Recipient->Sending->find(
				'first', 
				array(
					'conditions' => array('Sending.id' => $sending_id),
					'contain' => array('Mail'),
					'recursive' => -1
				)
			)
		);
	}
	
	
	public function showErrors($sending_id) {
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE'
		);
		
		$this->paginate = array('Recipient' => array('recursive' => -1, 'order' => array('Recipient.member_email' =>  'desc')));
		
		$this->set(
			'sendedError',
			$this->paginate(
				'Recipient',
				array(
					'Recipient.sending_id' => $sending_id,
					'Recipient.errors' => 1
				)
			)
		);
		
		$this->set(
			'sendedErrorCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
						'Recipient.errors' => 1
					)
				)
			)
		);
		
		$this->set(
			'allCount', 
			$this->Recipient->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array(
						'Recipient.sending_id' => $sending_id,
					)
				)
			)
		);
		
		$this->set(
			'sending', 
			$this->Recipient->Sending->find(
				'first', 
				array(
					'conditions' => array('Sending.id' => $sending_id),
					'contain' => array('Mail'),
					'recursive' => -1
				)
			)
		);
	}
	
	
	public function openMe() {
		
		//file_put_contents('/tmp/asd/', var_export(get_browser(null, true))); exit;
		App::uses('Url', 'Utility');
		//debug(Url::isAbsolute($this->request->query['uri'])); exit;
		if(
			isset($this->request->query['recipient']) && 
			isset($this->request->query['key']) && 
			!empty($this->request->query['recipient']) &&
			!empty($this->request->query['key'])
		) 
		{
			
			$recipient = $this->Recipient->getToOpenBySecret($this->request->query['recipient'], $this->request->query['key']);

			if(isset($recipient['Recipient']['id']) && !$recipient['Recipient']['opened']) {
				$this->Recipient->id = $recipient['Recipient']['id'];
				$browserInfo = get_browser();
				$fields['opened'] = true;
				$fields['opened_time'] = date('Y-m-d H:i:s');
				$fields['device'] = $browserInfo->ismobiledevice?'Mobile':'Pc';
				$fields['os'] = $browserInfo->platform;
				$fields['browser'] = $browserInfo->browser;
				

				$geoInfo = geoip_record_by_name(
					isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST'])?
						$_SERVER['HTTP_X_FORWARDED_HOST']:$_SERVER['REMOTE_ADDR']
				);
				
				if($geoInfo) {
					if(isset($geoInfo['country_code']) && !empty($geoInfo['country_code'])) {
						$fields['country'] = $geoInfo['country_code'];
						if(isset($geoInfo['region']) && !empty($geoInfo['region'])) {
							$region = geoip_region_name_by_code($geoInfo['country_code'], $geoInfo['region']);
							if($region) {
								$fields['region'] = $region;
							}
						}
					}
					if(
						isset($geoInfo['latitude']) && 
						isset($geoInfo['longitude']) && 
						!empty($geoInfo['latitude']) && 
						!empty($geoInfo['longitude'])
					) {
						$fields['lat'] = $geoInfo['latitude'];
						$fields['lon'] = $geoInfo['longitude'];
					}
				}
				
				$this->Recipient->save($fields);
			}
			
			if(isset($this->request->query['uri'])) {
				 if(!empty($this->request->query['uri']) && Url::isAbsolute($this->request->query['uri']))  {
				 	if(!isset($this->request->query['fromimage'])) {
				 		if(!$this->Recipient->Link->find('count', array(
				 			'recursive' => -1,
				 			'conditions' => array(
				 				'recipient_id' => $recipient['Recipient']['id'],
				 				'url' => $this->request->query['uri']
				 			)
				 		))) {
				 		
				 			$this->Recipient->Link->create();
				 			$this->Recipient->Link->save(array(
				 				'url' => $this->request->query['uri'], 
				 				'recipient_id' => $recipient['Recipient']['id'],
				 				'date' => date('Y-m-d H:i:s'),
				 				'sending_id' => $recipient['Recipient']['sending_id']
				 			));
				 		
				 		}
				 	}
				 	$this->redirect($this->request->query['uri']);
				 }
				 else {
				 	$this->redirect('/brokenLink');
				 }
			}
			else {
				header('Content-Type:image/png');
				die(file_get_contents(IMAGES.'openme.png'));
				$this->response->statusCode(200);
				$this->response->type('image/png');
				$this->response->body(file_get_contents(IMAGES.'openme.png'));
				return $this->response;
			}
		}
		else {
			$this->redirect('/brokenLink');
		}
		
	}
	
	
	public function view($id = null) {
		
		$this->Recipient->recursive = -1;
		$this->set('recipient', $this->Recipient->read(null, $id));
		
	}
	

	public function beforeFilter() {
		
		$this->Auth->allow('openMe');
		Configure::write('no_check_cookie', true);
		var_dump(Configure::read('no_check_cookie'));
		parent::beforeFilter();
		Configure::delete('no_check_cookie');
	}	

	
	protected function __securitySettings_showSended() {
		$this->Xuser->checkPerm($this->Recipient->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_showOpened() {
		$this->Xuser->checkPerm($this->Recipient->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_showErrors() {
		$this->Xuser->checkPerm($this->Recipient->Sending, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Recipient, isset($this->request->pass[0])?$this->request->pass[0]:null);
		if(isset($this->request->pass[1])) {
			$this->Xuser->checkPerm($this->Recipient->Sending, $this->request->pass[1]);
		}
		if(isset($this->request->params['named']['sending']) && !empty($this->request->params['named']['sending'])) {
			$this->Xuser->checkPerm($this->Recipient->Sending, $this->request->params['named']['sending']);
		}
	}

}
