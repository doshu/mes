<?php $this->element('js_variable_ckeditor'); ?>
<?php $this->set('title_for_layout', __('Modifica email')); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->script('attachment_upload_function', false); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb(h($this->request->data['Mail']['name']), '/mails/view/'.$this->request->data['Mail']['id']); ?>
<?php $this->Html->addCrumb('Modifica', '/mails/view/'.$this->request->data['Mail']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Modifica Email'); ?></h3>
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
					'onclick' => "$('#MailEditForm').submit();"
				)
			);
		
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-undo"></i></span><span class="text">'.__('Annulla').'</span>',
				array('action' => 'view', $this->request->data['Mail']['id']), 
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
				<?php echo $this->Form->input('Mail.id'); ?>
				<div class="form-group">
					<label class="control-label" for="MailName"><?php echo __('Titolo'); ?></label>
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
					<label class="control-label" for="MailSubject"><?php echo __('Oggetto'); ?></label>
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
						<?php 
							if(!isset($this->data['Attachment'])) {
								$this->request->data['Attachment'] = array();
							}
						?>
						<?php foreach($this->data['Attachment'] as $attachment) : ?>
							<div class="row">
								<div class="col-lg-12 attachment-upload-el">
									<span class="filename"><?php echo $attachment['realname']; ?></span>
									<a href="#" class="edit-upload-remove error-message pull-right" data-attachment="<?=$attachment['id'];?>">
										<?php echo __('Rimuovi'); ?>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
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
