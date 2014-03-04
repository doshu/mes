<div class="mailinglistsMembers form">
<?php echo $this->Form->create('MailinglistsMember'); ?>
	<fieldset>
		<legend><?php echo __('Edit Mailinglists Member'); ?></legend>
	<?php
		echo $this->Form->input('member_id');
		echo $this->Form->input('mailinglist_id');
		echo $this->Form->input('id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('MailinglistsMember.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('MailinglistsMember.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Mailinglists Members'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
	</ul>
</div>
