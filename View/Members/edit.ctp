<?php $this->set('title_for_layout', __('Modifica Membro')); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php App::uses('Mailinglist', 'Model'); ?>
<?php
	if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
		$Mailinglist = new Mailinglist();
		$mailinglist = $Mailinglist->find(
			'first', 
			array(
				'recursive' => -1, 
				'conditions' => array('id' => $this->request->params['named']['from'])
			)
		);
	}
?>
<?php if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) : ?>
	<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
	<?php $this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglists/view/'.$mailinglist['Mailinglist']['id']); ?>
	<?php $this->Html->addCrumb(h($this->request->data['Member']['email']), '/members/view/'.$this->request->data['Member']['id']); ?>
	<?php $this->Html->addCrumb('Modifica', '/members/edit/'.$this->request->data['Member']['id']); ?>
<?php else: ?>
	<?php $this->Html->addCrumb('Membri', '/members/index'); ?>
	<?php $this->Html->addCrumb(h($this->request->data['Member']['email']), '/members/view/'.$this->request->data['Member']['id']); ?>
	<?php $this->Html->addCrumb('Modifica', '/members/edit/'.$this->request->data['Member']['id']); ?>
<?php endif; ?>

<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuovo Membro'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Member', array('method' => 'post')); ?>
	<?php echo $this->Form->input('id'); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Salva'),
					'onclick' => "$('#MemberEditForm').submit();"
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
					<label class="control-label" for="MemberEmail"><?php echo __('Email'); ?></label>
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
								$cfId = $this->request->data['Memberfieldvalue__'.$additionalField['Memberfield']['code']]['id'];
								$cfValue = $this->request->data['Memberfieldvalue__'.$additionalField['Memberfield']['code']]['value_'.$fieldType];
				
								if($fieldType == 'date') {
									$cfValue = $this->SafeDate->dateForUser(
										DateTime::createFromFormat('Y-m-d', $cfValue), 
										'd/m/Y'
									); 
								}
								
								$valueField = $fieldType == 'boolean'?'checked':'value';
								
				
								echo $this->Form->input(
									'Memberfieldvalue.'.$addFieldCount.'.id', 
									array(
										'label' => false,
										'div' => false,
										'value' => $cfId
									)
								);
								 
								echo $this->Form->input(
									'Memberfieldvalue.'.$addFieldCount.'.value_'.$fieldType, 
									array(
										'label' => false,
										'div' => false,
										'type' => $inputType,
										'class' => 'form-control custom-field-'.$fieldType,
										$valueField => $cfValue
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



