<?php //debug($_SESSION); ?>
<?php $this->set('title_for_layout', __('Nuova Lista')); ?>
<?php $this->set('active', 'list'); ?>
<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
<?php $this->Html->addCrumb('Nuova', '/mailinglists/add'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuova Lista'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Mailinglist', array('method' => 'post')); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#MailinglistAddForm').submit();"
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
					<label class="control-label required" for="MailinglistName"><?php echo __('Nome'); ?></label>
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
					<label class="control-label" for="MailinglistDescription"><?php echo __('Descrizione'); ?></label>
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
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
