<?php //debug($_SESSION); ?>
<?php $this->set('title_for_layout', __('Nuovo Indirizzo')); ?>
<?php $this->set('active', 'smtp'); ?>
<?php $this->Html->addCrumb('Indirizzi Invio', '/smtps/index'); ?>
<?php $this->Html->addCrumb('Nuovo', '/smtps/add'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuovo Indirizzo di Invio'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Smtp', array('method' => 'post')); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#SmtpAddForm').submit();"
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
					<label class="control-label required" for="SmtpEmail"><?php echo __('Email'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'email', 
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
					<label class="control-label required" for="SmtpName"><?php echo __('Nome'); ?></label>
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
					<label class="control-label required" for="SmtpHost"><?php echo __('Host'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'host', 
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
					<label class="control-label required" for="SmtpPort"><?php echo __('Porta'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'port', 
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
					<label class="control-label required" for="SmtpUsername"><?php echo __('Username'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'username', 
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
					<label class="control-label required" for="SmtpPassword"><?php echo __('Password'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'password', 
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
					<label class="control-label required" for="SmtpEnctype"><?php echo __('Metodo Autenticazione'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'authtype', 
								array(
									'label' => false,
									'div' => false,
									'options' => array(
										'LOGIN' => 'LOGIN',
										'PLAIN' => 'PLAIN',
										'NTLM' => 'NTLM',
										'CRAM-MD5' => 'CRAM-MD5'
									)
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label required" for="SmtpEnctype"><?php echo __('Cifratura'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'enctype', 
								array(
									'label' => false,
									'div' => false,
									'options' => array('none' => __('Nessuna'), 'ssl' => __('SSL'), 'tls' => __('TLS'))
								)
							); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?php echo $this->Form->end(); ?>

