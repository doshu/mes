<?php 
	App::uses('File', 'Utility');
	App::uses('CakeNumber', 'Utility');
	$file = new File($file);
	$fileInfo = $file->info();
	
?>
<div class="thumbnail" draggable="true" data-image="<?php echo Router::fullBaseUrl().FileManager::getFullUrl($file->path); ?>">
	<?php 
		
		echo $this->Html->image(
			DS.FileManager::stripWebroot(
				$this->Image->resize($file->path, 100, 100)
			)
		); 
		
	?>
	<b title="<?php echo $fileInfo['basename']; ?>"><?php echo $fileInfo['basename']; ?></b>
	<p><?php echo CakeNumber::toReadableSize($fileInfo['filesize']); ?></p>
</div>
