<?php

App::uses('FileManagerAppModel', 'FileManager.Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');


class FileManager extends FileManagerAppModel {
	
	public $useTable = false;
	
	public $allowedExtension = array(
		'jpg',
		'jpeg',
		'gif',
		'png',
		'webp',
		'svg',
		'bmp',
		'ico'
	);
	
	public $allowedMimeType = array(
		'image/jpeg',
		'image/pjpeg',
		'image/jpg',
		'image/gif',
		'image/png',
		'image/webp',
		'image/svg',
		'image/svg+xml',
		'image/bmp',
		'image/x-windows-bmp',
		'image/x-icon',
		'image/ico'
	);
	
	public $fileContentChecker = '/usr/bin/file';
	public $fileContentCheckerCommand = '%s -b --mime-type %s';
	
	public function validateFileType($extension, $file) {
		if(file_exists($this->fileContentChecker)) {
			$type = exec(sprintf($this->fileContentCheckerCommand, $this->fileContentChecker, $file), $out);
			
			if(!in_array($type, $this->allowedMimeType))
				return false;
		}
		return in_array($extension, $this->allowedExtension);
	}
	
	public function getUserMedia($userId) {
		
		$path = FILE_MANAGER_PATH.$userId.DS;
		$dir = new Folder($path, true, 0775);
		$files = $dir->find($this->__getExtRegExp(), true);
		foreach($files as &$file) {
			$file = $path.$file;
		}
		return $files;
	}
	
	public function uploadUserMedia($uploadedFile, $userId) {
		$path = FILE_MANAGER_PATH.$userId.DS;
		$newFileName = $this->__getUploadedFileNewName($uploadedFile['name'], $path);
		if(@move_uploaded_file($uploadedFile['tmp_name'], $path.$newFileName))
			return $this->getFullUrl($path.$newFileName);
		return false;
	}
	
	public function deleteUserMedia($file, $userId) {
		$file = $this->securePath(FILE_MANAGER_PATH.$userId.DS.$file);
		if(file_exists($file) && is_writable($file)) {
			return unlink($file);
		}
		throw new NotFoundException();
	}
	
	public function getCurrentUserQuota() {
		$userQuota = AuthComponent::user('filemanager_quota');
		$userQuota = $userQuota?$userQuota:FILE_MANAGER_DEFAULT_QUOTA;
		$path = $this->securePath(FILE_MANAGER_PATH. AuthComponent::user('id'));
		$folder = new Folder($path, true, 0775);
		$used = $folder->dirsize();
		return array('used' => $used, 'total' => $userQuota);
	}
	
	public function freeSpaceAvailable() {
		$quota = $this->getCurrentUserQuota();
		return $quota['total'] - $quota['used'];
	}
	
	private function __getUploadedFileNewName($file, $path) {
		$dir = new Folder($path, true, 0775);
		$fileInfo = pathinfo($file);
		$equalFile = $dir->find($fileInfo['filename'].'\\.'.$fileInfo['extension'], false);
		if(empty($equalFile)) {
			return $fileInfo['basename'];
		}
		else {
			//^nomefile_(\d)\..*$/i
			$numberedFiles = $dir->find($fileInfo['filename'].'_\d\\.'.$fileInfo['extension'], false);
			if(empty($numberedFiles))
				return $fileInfo['filename'].'_1.'.$fileInfo['extension'];
			
			$current = 0;
			foreach($numberedFiles as $numberedFile) {
				$numberedFileInfo = pathinfo($numberedFile);
				preg_match('/^.*_(\d)$/i', $numberedFileInfo['filename'], $matches);
				if(isset($matches[1]) && $matches[1] > $current) {
					$current = $matches[1];
				}
			}
			
			return $fileInfo['filename'].'_'.++$current.'.'.$fileInfo['extension'];
		}
		
	}
	
	private function __getExtRegExp() {
		return '.*\\.'.implode('|',$this->allowedExtension);
	}
	
	
	//ritorna l'url relativa alla cakephp webroot
	public static function getUrl($file) {
		return '/'.str_replace(WWW_ROOT, '', FILE_MANAGER_WEBROOT.str_replace(FILE_MANAGER_PATH, '', $file));
	}
	
	//ritorna l'url relativa alla apache webroot
	public static function getFullUrl($file) {
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', FILE_MANAGER_WEBROOT.str_replace(FILE_MANAGER_PATH, '', $file));
	}
	
	public static function stripWebroot($file) {
		return str_replace(WWW_ROOT, '', $file);
	}
	
	public static function securePath($path) {
		return preg_replace('/\.{2,}/i', '', $path);
	}
}
