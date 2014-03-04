<?php
App::uses('AppController', 'Controller');
/**
 * MailinglistsMembers Controller
 *
 * @property MailinglistsMember $MailinglistsMember
 */
class MailinglistsMembersController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->MailinglistsMember->recursive = 0;
		$this->set('mailinglistsMembers', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->MailinglistsMember->exists($id)) {
			throw new NotFoundException(__('Invalid mailinglists member'));
		}
		$options = array('conditions' => array('MailinglistsMember.' . $this->MailinglistsMember->primaryKey => $id));
		$this->set('mailinglistsMember', $this->MailinglistsMember->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->MailinglistsMember->create();
			if ($this->MailinglistsMember->save($this->request->data)) {
				$this->Session->setFlash(__('The mailinglists member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mailinglists member could not be saved. Please, try again.'));
			}
		}
		$members = $this->MailinglistsMember->Member->find('list');
		$mailinglists = $this->MailinglistsMember->Mailinglist->find('list');
		$this->set(compact('members', 'mailinglists'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->MailinglistsMember->exists($id)) {
			throw new NotFoundException(__('Invalid mailinglists member'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MailinglistsMember->save($this->request->data)) {
				$this->Session->setFlash(__('The mailinglists member has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The mailinglists member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MailinglistsMember.' . $this->MailinglistsMember->primaryKey => $id));
			$this->request->data = $this->MailinglistsMember->find('first', $options);
		}
		$members = $this->MailinglistsMember->Member->find('list');
		$mailinglists = $this->MailinglistsMember->Mailinglist->find('list');
		$this->set(compact('members', 'mailinglists'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->MailinglistsMember->id = $id;
		if (!$this->MailinglistsMember->exists()) {
			throw new NotFoundException(__('Invalid mailinglists member'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MailinglistsMember->delete()) {
			$this->Session->setFlash(__('Mailinglists member deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Mailinglists member was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
