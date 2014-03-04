<?php
App::uses('FileManagerAppController', 'FileManager.Controller');
App::uses('File', 'Utility');
/**
 * FileManagers Controller
 *
 */
class FileManagersController extends FileManagerAppController {

	public $layout = 'one_column';
	
	public $helpers = array('FileManager.Image', 'Javascript', 'Phpjs');

	public function browse() {
		$userMedia = $this->FileManager->getUserMedia($this->Auth->user('id'));
		$this->set('quota', $this->FileManager->getCurrentUserQuota());
		$this->set('userMedia', $userMedia);
	}
	
	public function upload() {
		try {
			if($this->request->data['file']['error'] == UPLOAD_ERR_OK) {
				if($this->request->data['file']['size'] < $this->FileManager->freeSpaceAvailable()) {
					$file = new File($this->request->data['file']['name']);
					if($this->FileManager->validateFileType($file->ext(), $this->request->data['file']['tmp_name'])) {
						if($newFile = $this->FileManager->uploadUserMedia($this->request->data['file'], $this->Auth->user('id'))) {
							$response = array('status' => true, 'file' => $newFile);
						}
						else {
							throw new Exception(__("Errore durante l'upload. Riprovare."));
						}
					}
					else {
						throw new Exception(__("Formato del file non valido."));
					}
				}
				else {
					throw new Exception(__("Spazio su disco insufficiente"));
				}
			}
			else {
				throw new Exception(__("Errore durante l'upload. Riprovare."));
			}
		}
		catch(Exception $e) {
			$response = array('status' => false, 'error' => $e->getMessage());
		}
		
		$this->set('response', $response);
		$this->set('_serialize', array('response'));
	}
	
	public function renderThumbnail($base64_uri) {
		$this->layout = false;
		if(!$base64_uri)
			throw new NotFoundException();
		
		
		$FileManager = ClassRegistry::init('FileManager.FileManager');
		$file = $FileManager::securePath($_SERVER['DOCUMENT_ROOT'].base64_decode($base64_uri));
		if(file_exists($file)) {
			$this->set('file', $file);
		}
		else {
			
			throw new NotFoundException();
		}
	}
	
	protected function __securitySettings_upload() {
		$this->Security->validatePost = false;
		$this->Security->csrfUseOnce = false;
		$this->Security->requirePost();
	}
	
	
	public function delete() {
		$file = pathinfo($this->request->data['file']);
		$this->set('response', $this->FileManager->deleteUserMedia($file['basename'], $this->Auth->user('id')));
		$this->set('_serialize', array('response'));
	}
	
	protected function __securitySettings_delete() {
		$this->Security->validatePost = false;
		$this->Security->csrfUseOnce = false;
		$this->Security->requirePost();
	}

}
