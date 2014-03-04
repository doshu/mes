<?php //debug($_SESSION); ?>
<?php $this->set('title_for_layout', __('Modifica Campo Membro')); ?>
<?php $this->set('active', 'member'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Modifica Campo Membro'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Memberfield', array('method' => 'post')); ?>
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
					'onclick' => "$('#MemberfieldEditForm').submit();"
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
					<label class="control-label" for="MemberfieldName"><?php echo __('Nome'); ?></label>
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
					<label class="control-label" for="MemberfieldCode"><?php echo __('Codice'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'code', 
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
					<label class="control-label" for="MemberfieldType"><?php echo __('Tipo'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'type', 
								array(
									'label' => false,
									'div' => false,
									'options' => array(
										0 => __('Campo Testo'),
										1 => __('Area Di Testo'),
										2 => __('Sì/No'),
										3 => __('Data')
									)
								)
							); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label" for="MemberfieldInGrid"><?php echo __('Mostra Nella Griglia'); ?></label>
					<div>
						<?php 
							echo $this->Form->input(
								'in_grid', 
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
