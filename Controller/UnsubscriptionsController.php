<?php
App::uses('AppController', 'Controller');

class UnsubscriptionsController extends AppController {

	public function showSended($sending_id) {
		
		
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE'
		);
		
		$this->paginate = array('Recipient' => array('order' => array('Recipient.member_email' =>  'desc')));
		
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
		
		$this->set('sending', $this->Recipient->Sending->read(null, $sending_id));
	}
	
	
	public function view($id = null) {
		$this->set('unsubscription', $this->Unsubscription->find(
			'first', 
			array(
				'recursive' => 2, 
				'conditions' => array('Unsubscription.id' => $id),
				'contain' => array(
					'Sending' => array('Mail'),
					'Member',
					'Mailinglist'
				)
			)
		));	
			
		if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
			if(
				$this->request->params['named']['from'] == 'mailinglists' &&
				isset($this->request->params['named']['mailinglist']) && 
				!empty($this->request->params['named']['mailinglist'])
			) {
				$mailinglist = $this->Unsubscription->Mailinglist->find(
					'first',
					array('recursive' => -1, 'conditions' => array('id' => $this->request->params['named']['mailinglist']))
				);
				$this->set('mailinglist', $mailinglist);
			}
		}
	}
	
	
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Unsubscription, isset($this->request->pass[0])?$this->request->pass[0]:null);
		if(isset($this->request->params['named']['mailinglist_id']) && !empty($this->request->params['named']['mailinglist_id'])) {
			$this->Xuser->checkPerm($this->Unsubscription->Mailinglist, $this->request->params['named']['mailinglist_id']);
		}
	}
	
	

}
