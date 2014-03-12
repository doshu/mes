<?php echo $this->Html->link(__('Seleziona visibili'), '#', array('class' => 'select-visible-trigger')); ?> | 
<?php echo $this->Html->link(__('Deseleziona visibili'), '#', array('class' => 'unselect-visible-trigger')); ?>
<?php 
	$this->Form->unlockField('selected');
	echo $this->Form->input(
		'selected', 
		array(
			'type' => 'hidden', 
			'value' => '', 
			'id' => false,
			'class' => 'selected-list'
		)
	); 
?>
