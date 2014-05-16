<?php $this->element('js_variable_ckeditor'); ?>
<?php $this->set('title_for_layout', __('Nuovo Template')); ?>
<?php $this->set('active', 'template'); ?>
<?php $this->Html->script('textvars', false); ?>
<?php $this->Html->addCrumb('Template', '/templates/index'); ?>
<?php $this->Html->addCrumb('Nuovo', '/templates/add'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuovo Template'); ?></h3>
		<span class="line"></span>
	</div>
</div>
									
<?php echo $this->Form->create('Template', array('method' => 'post')); ?>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
			'#',
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Salva'),
				'onclick' => "$('#TemplateAddForm').submit();"
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
				<label for="TemplateName" class="control-label required"><?php echo __('Titolo'); ?></label>
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
				<label class="control-label" for="TemplateDescription"><?php echo __('Descrizione'); ?></label>
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
				<label class="control-label" for="TemplateHtml"><?php echo __('Formato HTML'); ?></label>
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
				<label class="control-label" for="TemplateText"><?php echo __('Formato testo'); ?></label>
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
	</div>
</div>
<?php echo $this->Form->end(); ?>
