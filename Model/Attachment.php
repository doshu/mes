<?php
App::uses('AppModel', 'Model');
App::uses('CakeEventListener', 'Event');

class Attachment extends AppModel {


	private $__attachmentToDelete = null;

	public $validate = array(
		'path' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'mail_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Questo campo non può essere vuoto',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Mail' => array(
			'className' => 'Mail',
			'foreignKey' => 'mail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
    
    
    public function __deleteAttachment($path) {
    	Configure::load('path');
		$attachmentPath = Configure::read('Path.Attachment');
		@unlink($attachmentPath.$path);
    }

	
	private function createTempFilename() {
		
		do {
			$tmpName = md5(microtime().uniqid('', true));
		} while( $this->tempNameExists($tmpName) );
		
		return $tmpName;
	}
	
	private function tempNameExists($name) {
		App::uses('Tempattachment', 'Model');
		$Tempattachment = new Tempattachment();
		
		$inAttachment = $this->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('path' => $name)
			)
		);
		
		$inTempAttachment = $Tempattachment->find(
			'count',
			array(
				'recursive' => -1,
				'conditions' => array('tempname' => $name)
			)
		);
		
		if($inAttachment || $inTempAttachment)
			return true;
		
		return false;
		
	}
	
	public function getUploaded($file, $user_id) {
		
		App::uses('Tempattachment', 'Model');
		$Tempattachment = new Tempattachment();
		
		if($file['error'] == UPLOAD_ERR_OK) {
			Configure::load('path');
			$sandboxPath = Configure::read('Path.Sandbox');
			$newFileName = $this->createTempFilename();
			if(move_uploaded_file($file['tmp_name'], $sandboxPath.$newFileName)) {
			
				$Tempattachment->create();
				$tempAttachmentSave = $Tempattachment->save(array(
					'tempname' => $newFileName,
					'realname' => $file['name'],
					'validated' => 1,
					'user_id' => $user_id
				));
				
				if($tempAttachmentSave) {
					return array(
						'status' => 1,
						'attachment' => $Tempattachment->id
					);
				}
				else {
					@unlink($sandboxPath.$newFileName);
					return array('status' => 0, 'error' => __('Errore Upload allegato'));
				}
			}
			else {
				return array('status' => 0, 'error' => __('Errore Upload allegato'));
			}
		}
		else {
			$error = "";
			switch($file['error']) {
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error = __('Dimensione allegato troppo grande');
				break;
				default:
					$error = __('Errore Upload allegato');
			}
			
			return array('status' => 0, 'error' => $error);
		}
	}
	
	
	public function parseMailAttachment($data, $user_id) {
	
		App::uses('Tempattachment', 'Model');
		$Tempattachment = new Tempattachment();
		$return = array();
		
		Configure::load('path');
		$sandboxPath = Configure::read('Path.Sandbox');
		$attachmentPath = Configure::read('Path.Attachment');
		
		foreach($data['path'] as $attachment) {
			$entry = $Tempattachment->read(null, $attachment);
			if($entry['Tempattachment']['user_id'] == $user_id && $entry['Tempattachment']['validated']) {
				if(
					file_exists($sandboxPath.$entry['Tempattachment']['tempname']) &&
					!file_exists($attachmentPath.$entry['Tempattachment']['tempname']) &&
					rename($sandboxPath.$entry['Tempattachment']['tempname'], $attachmentPath.$entry['Tempattachment']['tempname'])
				) {
					$return[] = array(
						'realname' => $entry['Tempattachment']['realname'],
						'path' => $entry['Tempattachment']['tempname']
					);
					$Tempattachment->delete();
				}
				else {
					return false;
				}
			}
		}
		return $return;
	}
	
	
	public function beforeDelete($cascade = true) {
		$attachment = $this->read('path', $this->id);
		$this->__attachmentToDelete = $attachment['Attachment']['path'];
		return true;
	}
	
	public function afterDelete() {
		$this->__deleteAttachment($this->__attachmentToDelete);
	}
	
	
	public function checkPerm($id, $params, $user) {
		$data = $this->read(null, $id);
		if(isset($data['Mail']['user_id']) && !empty($data['Mail']['user_id']))
			return $data['Mail']['user_id'] == $user;
		throw new NotFoundException();
	}
}
