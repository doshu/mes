<?php //debug($_SESSION); ?>
<?php $this->set('title_for_layout', __('Nuovo Membro')); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php $this->Html->addCrumb('Membri', '/members/index'); ?>
<?php $this->Html->addCrumb('Nuovo', '/members/add'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuovo Membro'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Member', array('method' => 'post')); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#MemberAddForm').submit();"
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
				<div class="form-group	">
					<label class="control-label required" for="MemberEmail"><?php echo __('Email'); ?></label>
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
				<?php $addFieldCount = 0; ?>
				<?php foreach($memberAdditionalFields as $additionalField) : ?>
					<?php $fieldType = Memberfield::$dataType[$additionalField['Memberfield']['type']]; ?>
					<div class="form-group">
						<label class="control-label" for="<?php echo 'Memberfieldvalue'.$addFieldCount.'Value'.ucfirst($fieldType) ?>">
							<?php echo ucfirst(__($additionalField['Memberfield']['name'])); ?>
						</label>
						<div>
							<?php
								$inputType = $fieldType == 'date'?'text':null;
								echo $this->Form->input(
									'Memberfieldvalue.'.$addFieldCount.'.value_'.$fieldType, 
									array(
										'label' => false,
										'div' => false,
										'type' => $inputType,
										'class' => 'form-control custom-field-'.$fieldType
									)
								);
								echo $this->Form->input(
									'Memberfieldvalue.'.$addFieldCount.'.memberfield_id', 
									array(
										'label' => false,
										'div' => false,
										'type' => 'hidden',
										'value' => $additionalField['Memberfield']['id']
									)
								);  
							?>
						</div>
					</div>
				<?php $addFieldCount++; ?>
				<?php endforeach; ?>
				<div class="form-group">
					<label class="control-label" for="MailinglistMailinglist"><?php echo __('Liste'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'Mailinglist', 
								array(
									'label' => false,
									'div' => false,
								)
							); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
