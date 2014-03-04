<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('surname');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('smtp_host');
		echo $this->Form->input('smtp_port');
		echo $this->Form->input('smtp_enctype');
		echo $this->Form->input('smtp_username');
		echo $this->Form->input('smtp_password');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mails'), array('controller' => 'mails', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mail'), array('controller' => 'mails', 'action' => 'add')); ?> </li>
	</ul>
</div>
