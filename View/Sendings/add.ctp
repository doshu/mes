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
<?php echo $this->Form->create('Sending', array('method' => 'post', 'inputDefaults' => array('autocomplete' => 'off'))); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-plane"></i></span><span class="text">'.__('Invia').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Invia'),
					'onclick' => "$('#SendingAddForm').submit();"
				)
			);
		
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-undo"></i></span><span class="text">'.__('Annulla').'</span>',
				array('controller' => 'mails', 'action' => 'view', $mail['Mail']['id']), 
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
						<?php 
							echo $this->Form->input(
								'Mailinglist', 
								array(
									'label' => false,
									'options' => $Mailinglist,
									'multiple' => true
								)
							); 
						?>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingSmtp"><?php echo __('Abilita Condizioni'); ?></label>
					<?php 
						echo $this->Form->input(
							'enable_conditions', 
							array(
								'label' => false,
								'type' => 'checkbox',
								'value' => 1
							)
						); 
					?>
				</div>
			</div>
		</div>	
		<div id="filterContainer">
			<div><label><?php echo __('Filtro destinatari'); ?></label><span class="adder" id="top-adder"><i class="fa fa-plus"></i></span></div>
			<ul id="conditions" class="list-unstyled">
				<li>
					<ul id="startingConditionsList">
						<li class="conditions-el undeletable">
							<?php 
								$conditionsTemplate =  $this->Form->input(
									'conditions.0.value', 
									array(
										'label' => false,
										'options' => array(
											__('Operatori') => array(
												'and' => __('Se tutte le condizioni sono soddisfatte'),
												'or' => __('Se una delle condizioni è soddisfatta'),
											),
											__('Controlli') => array(
												'member_sice' => __('Iscritto alla lista da/a'),
												'unsubscribing' => __('Numero di disiscrizioni da/a'),
												'sendings' => __('Numero di email inviate da/a'),
												'opened' => __('Numero di email lette da/a')
											)
										),
										'empty' => __('Seleziona un opzione'),
										'data-code' => 0,
										'data-name-template' => 'data[conditions][__code__][value]',
										'class' => 'conditions-el-select'
									)
								); 
						
								echo $conditionsTemplate;
							?>
						</li>
					</ul>
				</li>	
			</ul>
			<p>
				<?php $testUrl = $this->Html->url(array('action' => 'testConditions', 'ext' => 'json')); ?>
				<a href="Javascript:void(0);" id="testFilter" class="label label-primary" data-url="<?php echo $testUrl; ?>">
					<i class="fa fa-filter"></i> <?php echo __('Test filtro'); ?>
				</a>
			</p>
		</div>
		<div class="row">
			<div class="col-lg-8">
				<div class="form-group">
					<label class="control-label" for="SendingSmtp"><?php echo __('Invia da'); ?></label>
						<?php 
							echo $this->Form->input(
								'smtp_id', 
								array(
									'label' => false,
								)
							); 
						?>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingSmtp"><?php echo __('Nome Mittente'); ?></label>
						<?php 
							echo $this->Form->input(
								'sender_name', 
								array(
									'label' => false,
									'class' => 'form-control'
								)
							); 
						?>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingType"><?php echo __('Invia Come'); ?></label>
						<?php 
							echo $this->Form->input(
								'type', 
								array(
									'label' => false,
									'options' => $typeOptions
								)
							); 
						?>
				</div>
				<div class="form-group">
					<label class="control-label" for="SendingTime"><?php echo __('Invio programmato'); ?></label>
						<?php 
							echo $this->Form->input(
								'enableTime', 
								array(
									'label' => false,
									'type' => 'checkbox',
									'class' => 'form-control'
								)
							); 
						?>
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
						<?php 
							echo $this->Form->input(
								'note', 
								array(
									'label' => false,
									'class' => 'form-control'
								)
							); 
						?>
				</div>
			</div>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
<?php 
	echo $this->Javascript->setGlobal(array(
		'conditionsElementTemplate' => $conditionsTemplate,
		'mailinglist' => $Mailinglist
	)); 
?>
