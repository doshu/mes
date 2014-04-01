<?php 
	App::uses('File', 'Utility');
	App::uses('FileManager', 'FileManager.Model'); 
	App::uses('CakeNumber', 'Utility');	
	$this->Javascript->setGlobal(array('THUMB_TEMPLATE_URL' => Router::url(array('action' => 'renderThumbnail'))));
	$this->Javascript->setGlobal(array('UPLOAD_URL' => Router::url(array('action' => 'upload', 'ext' => 'json'))));
	$this->Javascript->setGlobal(array('DELETE_URL' => Router::url(array('action' => 'delete', 'ext' => 'json'))));
	$this->Javascript->setGlobal(array('TOKEN_KEY' => $this->request->params['_Token']['key']));
	$this->Phpjs->add('url/base64_encode');
	
?>
<div id="uploaderContainer" data-offset-top="0">
	<?php echo $this->Form->hidden('_Token.key', array('value' => $this->request->params['_Token']['key'])); ?>
	<span style="line-height:45px; margin-left:10px;">
		<?php 
			echo $this->Form->input(
				'file', 
				array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'u-hide')
			); 
		?>
		<button type="button" class="btn u-hide" id="loadButton"><?php echo __('Carica file'); ?></button>
		<span>
			<?php echo __('Usati').' '.CakeNumber::toReadableSize($quota['used']).' su '.CakeNumber::toReadableSize($quota['total'])?>
		</span>
		<script>
			if(Modernizr.xhr2) {
				$('#loadButton').removeClass('u-hide');
			} 
			else { 
				$('#file').removeClass('u-hide');
			}
		</script>
	</span>
	<div id="actionContainer">
		<a href="#" id="select">
			<i class="fa fa-check"></i>
		</a>
		<a href="#" id="trash">
			<i class="fa fa-trash-o"></i>
		</a>
	</div>
	
</div>
<div id="mediaContainer" class="clearfix">
	<div id="progressContainer"></div>
	<div id="thumbnailContainer">
		<?php foreach($userMedia as $file) :?>
			<?php
				$file = new File($file);
				$fileInfo = $file->info();
			?>
	
			<div class="thumbnail" draggable="true" data-image="<?php echo FILE_MANAGER_DOMAIN.FileManager::getFullUrl($file->path); ?>">
				<image src="" data-url="<?=Router::url(FileManager::getUrl($this->Image->resize($file->path, 100, 100))) ?>"/>
				<?php 
					/*
					echo $this->Html->image(
						FileManager::getUrl(
							$this->Image->resize($file->path, 100, 100)
						)
					); 
					*/
				?>
				<b title="<?php echo $fileInfo['basename']; ?>"><?php echo $fileInfo['basename']; ?></b>
				<p><?php echo CakeNumber::toReadableSize($fileInfo['filesize']); ?></p>
			</div>
	
		<?php endforeach; ?>
	</div>
</div>
