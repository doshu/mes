<?php $this->set('title_for_layout', __('Impostazioni Account')); ?>
<?php //$this->set('active', 'dash'); ?>
<?php $this->Html->addCrumb('Impostazioni Account', '/users/settings'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Impostazioni'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('User'); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#UserSettingsForm').submit();"
				)
			);
		
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-undo"></i></span><span class="text">'.__('Annulla').'</span>',
				array('controller' => 'mails', 'action' => 'dashboard'), 
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
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo __('Cambia Password'); ?>
					</div>
					<div class="panel-body nopadding clearfix">
						<div class="form-group">
							<label class="control-label" for="UserOldpwd"><?php echo __('Vecchia Password'); ?></label>
							<div>
								<?
									echo $this->Form->input(
										'oldpwd', 
										array('label' => false, 'type' => 'password', 'required' => true, 'class' => 'form-control')
									);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="UserNewpwd"><?php echo __('Nuova Password'); ?></label>
							<div>
								<?
									echo $this->Form->input(
										'newpwd', 
										array('label' => false, 'type' => 'password', 'required' => true, 'class' => 'form-control')
									);
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="UserNewpwd2"><?php echo __('Ripeti Password'); ?></label>
							<div>
								<?
									echo $this->Form->input(
										'newpwd2', 
										array('label' => false, 'type' => 'password', 'required' => true, 'class' => 'form-control')
									);
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>

