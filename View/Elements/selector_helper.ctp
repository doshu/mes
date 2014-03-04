<?php echo $this->Html->link(__('Seleziona tutto'), '#', array('class' => 'select-all-trigger')); ?> | 
<?php echo $this->Html->link(__('Seleziona visibili'), '#', array('class' => 'select-visible-trigger')); ?> | 
<?php echo $this->Html->link(__('Deseleziona tutto'), '#', array('class' => 'unselect-all-trigger')); ?>
<?php 
	echo $this->Form->input(
		'all', 
		array(
			'type' => 'hidden', 
			'value' => 0, 
			'name' => false, 
			'id' => false,
			'class' => 'is-all-selected'
		)
	); 
?>
