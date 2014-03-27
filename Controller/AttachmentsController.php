<?php
App::uses('AppController', 'Controller');


class AttachmentsController extends AppController {


	public function delete($id = null) {
		
		$this->Attachment->id = $id;
		
		//$filePath = $this->Attachment->read(null, $id);
		
		if ($this->Attachment->delete()) {

			if($this->request->is('json')) {
				$this->set('response', array('status' => 1));
				$this->set('_serialize', array('response'));
				return;
			}
			else {
				$this->Session->setFlash(__('Attachment deleted'));
				$this->redirect($this->referer(array('action' => 'index'), true));
			}
		}
		if($this->request->is('json')) {
			$this->set('response', array('status' => 0));
			$this->set('_serialize', array('response'));
			return;
		}
		else {
			$this->Session->setFlash(__('Attachment was not deleted'));
			$this->redirect($this->referer(array('action' => 'index'), true));
		}
	}
	
	
	
	
	public function upload() {
		
		if($this->request->isPost() && isset($_FILES['file'])) {
			$response = $this->Attachment->getUploaded($_FILES['file'], $this->Auth->user('id'));
		}
		else {
			$response['status'] = 0;
			$response['error'] = __('Errore Upload allegato', true);
		}
		
		$this->set('response', $response);
		
		$this->set('_serialize', array('response'));
	}
	
	
	
	
	public function download($id) {
		Configure::load('path');
		$path = Configure::read('Path.Attachment');
		$this->Attachment->recursive = -1;
		$attachment = $this->Attachment->read(null, $id);
		$this->response->file(
			$path.$attachment['Attachment']['path'], 
			array('download' => true, 'name' => $attachment['Attachment']['realname'])
		);
		return $this->response;
	}
	
	
	protected function __securitySettings_upload() {
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}
	
	
	protected function __securitySettings_delete() {
		$this->Security->csrfCheck = false;
		$this->request->onlyAllow('post', 'delete');
		$this->Xuser->checkPerm($this->Attachment, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
	
	
	protected function __securitySettings_download() {
		$this->Xuser->checkPerm($this->Attachment, isset($this->request->pass[0])?$this->request->pass[0]:null);
	}
}
