<?php $this->set('title_for_layout', __('Importa Membri')); ?>
<?php $this->set('active', 'member'); ?>
<?php $this->Phpjs->add('strings/htmlspecialchars', false); ?>
<?php App::uses('Member', 'Model'); ?>
<?php $this->Html->addCrumb('Membri', '/members/index'); ?>
<?php $this->Html->addCrumb('Importa', '/members/import'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Importa Membri'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Seleziona in file CSV da importare'); ?>
					<h5 style="float:right;">
						<?php
							echo $this->Html->link(
								__('Scarica esempio'),
								array('action' => 'example')
							);
						?>
					</h5>
				</div>
				<div class="panel-body">
					<?php
						echo $this->Form->create('Member', array('type' => 'file'));
						echo $this->Form->button(
							__('Seleziona file'), 
							array('type' => 'button', 'class' => 'btn btn-small btn-primary', 'id' => 'select-file')
						);
						echo $this->Form->input(
							'file', 
							array(
								'type' => 'file', 
								'class' => 'hide', 
								'label' => false, 
								'div' => false,
								'accept' => 'text/csv'
							)
						);
					?>
					<span id="selected-filename" style="vertical-align: middle; margin-left: 10px;"></span>
					<?php
						echo $this->Form->button(
							__('Importa'), 
							array('type' => 'submit', 'class' => 'btn btn-small btn-primary hide pull-right', 'id' => 'upload-file')
						);
					?>
					<label for="MemberImportExists" style="margin-top:10px;">
						<?php
							echo $this->Form->input(
								'importExists', 
								array(
									'type' => 'checkbox', 
									'label' => false,
									'div' => false,
									'style' => 'margin:0 3px 0 0;'
								)
							);
							echo __('Ignora se già esistenti e aggiungi alla lista');
						?>
					</label>
					<?php
						echo $this->Form->end(); 
					?>
					<div id="preview" class="hide">
						<div style="margin-top:20px;"><h4><?php echo __('Anteprima'); ?></h4></div>
					</div>
					<?php if(isset($errors)) : ?>
						<div id="error-container">
							<div class="error-header"><?php echo count($errors).' '.__("Errori durante l'importazione"); ?></div>
							<?php foreach($errors as $error) : ?>
								<div class="error-element">
									<?=$error;?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


