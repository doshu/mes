<div class="mailinglistsMembers view">
<h2><?php  echo __('Mailinglists Member'); ?></h2>
	<dl>
		<dt><?php echo __('Member'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mailinglistsMember['Member']['id'], array('controller' => 'members', 'action' => 'view', $mailinglistsMember['Member']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Mailinglist'); ?></dt>
		<dd>
			<?php echo $this->Html->link($mailinglistsMember['Mailinglist']['name'], array('controller' => 'mailinglists', 'action' => 'view', $mailinglistsMember['Mailinglist']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($mailinglistsMember['MailinglistsMember']['id']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Mailinglists Member'), array('action' => 'edit', $mailinglistsMember['MailinglistsMember']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Mailinglists Member'), array('action' => 'delete', $mailinglistsMember['MailinglistsMember']['id']), null, __('Are you sure you want to delete # %s?', $mailinglistsMember['MailinglistsMember']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Mailinglists Members'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglists Member'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
	</ul>
</div>
