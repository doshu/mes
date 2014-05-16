<?php $this->element('js_variable_ckeditor'); ?>
<?php $this->set('title_for_layout', __('Nuova email')); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->script('attachment_upload_function', false); ?>
<?php $this->Html->script('textvars', false); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb('Nuova', '/mails/add'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuova Email'); ?></h3>
		<span class="line"></span>
	</div>
</div>
									
<?php echo $this->Form->create('Mail', array('method' => 'post', 'type' => 'file')); ?>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
			'#',
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Salva'),
				'onclick' => "$('#MailAddForm').submit();"
			)
		);
		
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-undo"></i></span><span class="text">'.__('Annulla').'</span>',
			array('action' => 'index'), 
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Annulla'),
			)
		);
	?>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-8">
			<div class="form-group">
				<label for="MailName" class="control-label required"><?php echo __('Titolo'); ?></label>
				<div>
					<?php 
						echo $this->Form->input(
							'name', 
							array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							)
						); 
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label" for="MailDescription"><?php echo __('Descrizione'); ?></label>
				<div>
					<?php 
						echo $this->Form->input(
							'description', 
							array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							)
						); 
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label required" for="MailSubject"><?php echo __('Oggetto'); ?></label>
				<div>
					<?php 
						echo $this->Form->input(
							'subject', 
							array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							)
						); 
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label" for="MailHtml"><?php echo __('Formato HTML'); ?></label>
				<div>
					<?php 
						echo $this->Form->input(
							'html', 
							array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							)
						); 
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label" for="MailText"><?php echo __('Formato testo'); ?></label>
				<div>
					<?php 
						echo $this->Form->input(
							'text', 
							array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							)
						); 
					?>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __('Aggiungi allegati'); ?></div>
				<div class="panel-body attachment-upload-container">
					<?php if(isset($this->request->data['Attachment']['path']) && is_array($this->request->data['Attachment']['path'])): ?>					
						<?php foreach($this->request->data['Attachment']['path'] as $tempattachment) : ?>
							<div class="row">
								<div class="col-lg-12 attachment-upload-el">
									<span class="filename"><?php echo $tempattachment['name'].' ('.$tempattachment['size'].')'; ?></span>
									<input type="hidden" name="data[Attachment][path][]" value="<?php echo $tempattachment['id']; ?>">
									<a class="upload-remove error-message pull-right" href="#"><?php echo __('Rimuovi'); ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
					<div class="row">
						<div class="col-lg-12 attachment-upload-el">
							<?php
								echo $this->Form->input(
									'Attachment..path', 
									array(
										'type' => 'file', 
										'class' => 'attachment-upload-input u-hide',
										'label' => false,
										'div' => false,
										'name' => false,
									)
								);
							?>
							<button class="btn btn-success btn-xs upload-opener u-hide" type="button"><?php echo __('Seleziona'); ?></button>
							<script>
								if(Modernizr.xhr2) { 
									$('.upload-opener').removeClass('u-hide'); 
								} 
								else { 
									$('.attachment-upload-input').removeClass('u-hide');
								}
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
