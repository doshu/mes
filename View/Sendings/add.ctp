<?php
	App::uses('Sending', 'Model');
	$typeOptions = array(
		Sending::$HTML => __('HTML'),
		Sending::$TEXT => __('Testo'),
		Sending::$BOTH => __('Entrambi'), 
	);
?>

<?php $this->set('title_for_layout', __('Invia Email').' '.h($mail['Mail']['name'])); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb(h($mail['Mail']['name']), '/mails/view/'.$mail['Mail']['id']); ?>
<?php $this->Html->addCrumb('Invia', '/sendings/add/'.$mail['Mail']['id']); ?>

<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Invia Email').' '.h($mail['Mail']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Sending', array('method' => 'post')); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#SendingAddForm').submit();"
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
				<?php 
					echo $this->Form->input(
						'mail_id', 
						array(
							'label' => false,
							'div' => false,
							'type' => 'hidden',
							'value' => $mail_id
						)
					); 
				?>
				<div class="form-group">
					<label class="control-label" for="SendingMailinglist"><?php echo __('Liste Destinatari'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'Mailinglist', 
								array(
									'label' => false,
									'div' => false,
									'options' => $Mailinglist,
									'multiple' => true
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingSmtp"><?php echo __('Invia da'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'smtp_id', 
								array(
									'label' => false,
									'div' => false,
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingSmtp"><?php echo __('Nome Mittente'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'sender_name', 
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
					<label class="control-label" for="SendingType"><?php echo __('Invia Come'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'type', 
								array(
									'label' => false,
									'div' => false,
									'options' => $typeOptions
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingTime"><?php echo __('Invio programmato'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'enableTime', 
								array(
									'label' => false,
									'div' => false,
									'type' => 'checkbox',
									'class' => 'form-control'
								)
							); 
						?>
					</div>
					<div id="timeControls" style="display:none">
						<?php 
							echo $this->Form->input(
								'time', 
								array(
									'label' => false,
									'div' => false,
									'type' => 'text',
									'readonly' => 'readonly',
									'style' => 'width:100%;',
									'placeholder' => __('Clicca per inserire data e ora di invio'),
									'class' => 'form-control'
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingNote"><?php echo __('Note di invio'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'note', 
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
		</div>
	</div>
<?php echo $this->Form->end(); ?>
