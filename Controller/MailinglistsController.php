<?php
App::uses('AppController', 'Controller');


class MailinglistsController extends AppController {


	public function index() {
		$this->Mailinglist->recursive = -1;

		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'name' => 'LIKE',
			'description' => 'LIKE',
			'created' => array('BETWEEN', array('convert' => true, 'type' => 'date', 'time' => true)),
			'members_count' => array('BETWEEN', array('having' => true))
		);
		
		$this->paginate = array(
			'Mailinglist' => array(
				'recursive' => -1,
				'joins' => array(
					array(
						'table' => 'mailinglists_members',
						'alias' => 'MailinglistsMember',
						'type' => 'LEFT',
						'conditions' => array('Mailinglist.id = MailinglistsMember.mailinglist_id')
					)
				),
				'fields' => array(
					'Mailinglist.id', 
					'Mailinglist.name', 
					'Mailinglist.description', 
					'Mailinglist.created',
					'Mailinglist.members_count'
					
				),
				'order' => array('Mailinglist.created' =>  'desc'),
				'group' => array('Mailinglist.id')
			)
		);
		$this->Mailinglist->virtualFields['members_count'] = 'COUNT(member_id)';
		$mailinglists = $this->paginate('Mailinglist', array('Mailinglist.user_id' => $this->Auth->user('id')));
		unset($this->Mailinglist->virtualFields['members_count']);
		
		
		$this->set(
			'count', 
			$this->Mailinglist->find(
				'count', 
				array(
					'recursive' => -1, 
					'conditions' => array('Mailinglist.user_id' => $this->Auth->user('id'))
				)
			)
		);
		$this->set('avg', $this->Mailinglist->getAvg($this->Auth->user('id')));
		$this->set(compact('mailinglists'));
	}


	public function view($id = null) {

		$mailinglist = $this->Mailinglist->find('first', array('recursive' => -1, 'conditions' => array('id' => $id)));
		$mailinglist['Mailinglist']['members_count'] = $this->Mailinglist->getMembersCount($mailinglist['Mailinglist']['id']);
		$this->set(
			'unsubscribed', 
			$this->Mailinglist->MailinglistsUnsubscription->find('count', array(
				'recursive' => -1,
				'conditions' => array(
					'mailinglist_id' => $id
				)	
			))
		);
		$this->set('mailinglist', $mailinglist);
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Mailinglist->create();
			if (
				$this->Mailinglist->validateOnCreate($this->request->data) && 
				$this->Mailinglist->save($this->request->data, true, array(
					'Mailinglist' => array('name', 'description', 'created', 'modified', 'user_id')
				))
			) {
				$this->Session->setFlash(__('La Mailinglist è stata salvata'), 'default', array(), 'info');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		}
	}


	public function edit($id = null) {
		
		if ($this->request->is('post') || $this->request->is('put')) {
			if (
				$this->Mailinglist->validateOnEdit($this->request->data) && 
				$this->Mailinglist->save($this->request->data, true, array(
					'Mailinglist' => array('name', 'description', 'created', 'modified')
				))
			) {
				$this->Session->setFlash(__('La Mailinglist è stata salvata'), 'default', array(), 'info');
				$this->redirect(array('action' => 'view', $id));
			} else {
				$this->Session->setFlash(__('Errore durante il salvataggio. Riprovare'), 'default', array(), 'error');
			}
		} else {
			$this->request->data = $this->Mailinglist->find('first', array('conditions' => array('Mailinglist.id' => $id)));
		}
		
	}
	
	
	public function export($id = null) {
		
			
		$mailinglist = $this->Mailinglist->read('name', $id);
		$csv = $this->Mailinglist->Member->getCsv($this->Auth->user('id'), $id);
		$this->response->body($csv);
		$this->response->type('text/csv');
		$this->response->download('export_'.str_replace(' ', '_', $mailinglist['Mailinglist']['name'].'.csv'));

		return $this->response;
		
	}


	public function delete($id = null) {
		$this->Mailinglist->id = $id;
		if ($this->Mailinglist->delete()) {
			$this->Session->setFlash(__('La Mailinglist è stata eliminata'), 'default', array(), 'info');
			$this->redirect($this->referer(array('action' => 'index'), true));
		}
		$this->Session->setFlash(__('Errore durante la rimozione. Riprovare'), 'default', array(), 'error');
		$this->redirect($this->referer(array('action' => 'index'), true));
	}
	
	
	public function unsubscribed($id) {
	
		$this->set(
			'mailinglist', 
			$this->Mailinglist->find(
				'first', 
				array(
					'recursive' => -1,
					'conditions' => array('id' => $id),
				)
			)
		);
			
		$this->autoPaginate = true;
		$this->autoPaginateOp = array(
			'member_email' => 'LIKE'
		);
		
		$this->paginate = array(
			'Unsubscription' => array( 
				'recursive' => -1,
				'conditions' => array('MailinglistsUnsubscription.mailinglist_id' => $id),
				'joins' => array(
					array(
						'table' => 'mailinglists_unsubscriptions',
						'alias' => 'MailinglistsUnsubscription',
						'type' => 'LEFT',
						'conditions' => array(
							'Unsubscription.id = MailinglistsUnsubscription.unsubscription_id',
						)
    				)
				)
			)
		);
		
		$this->set('unsubscribeds', $this->paginate('Unsubscription'));
	}
	
	
	
	protected function __securitySettings_view() {
		$this->Xuser->checkPerm($this->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_unsubscribed() {
		$this->Xuser->checkPerm($this->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_edit() {
		$this->Xuser->checkPerm($this->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_export() {
		$this->Security->requirePost();
		$this->Xuser->checkPerm($this->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	protected function __securitySettings_delete() {
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Mailinglist, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
