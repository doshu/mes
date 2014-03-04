<?php

	App::uses('AppHelper', 'View/Helper');
	App::uses('Folder', 'Utility');

	class ImageHelper extends AppHelper {
	
		private function __resize($file, $width, $height, $proportion = true) {
			
			$thumb = new Imagick($file);
			if($proportion) {
				list($newWidth, $newHeight) = $this->getImageProportion($thumb->getImageWidth(), $thumb->getImageHeight(), $width, $height);
			}
			else {
				$newWidth = $width;
				$newHeight = $height;
			}
			
			
			$thumb->thumbnailImage($newWidth, $newHeight);
			
			$background = new Imagick();
			$background->newImage($width, $height, '#FFFFFF');
			$background->compositeImage(
				$thumb, 
				imagick::COMPOSITE_DEFAULT, 
				($width/2)-($newWidth/2),
				($height/2)-($newHeight/2)
			);
			
			return $background;
		}
		
		public function resize($file, $width, $height, $proportion = true, $cacheDir = null) {
			$file = pathinfo($file);
			
			if(!$cacheDir) {
				$cacheDir = $file['dirname'].DS.'cache'.DS;
				$newDir = new Folder($cacheDir, true);
			}
			
			if(!file_exists($cacheDir.DS.$file['basename']))  {
				
				$thumb = $this->__resize($file['dirname'].DS.$file['basename'], $width, $height, $proportion);
				$thumb->writeImage($cacheDir.DS.$file['basename']);
			}
			
			return $cacheDir.$file['basename'];
			
		}
		
		public function getImageProportion($oldWidth, $oldHeight, $newWidth, $newHeight) {
			
			if($oldWidth >= $oldHeight) {
				return array($newWidth, ($oldHeight*$newWidth)/$oldWidth);
			}
			else {
				return array(($oldWidth*$newHeight)/$oldHeight, $newHeight);
			}
			
		}
		
	}

?>
