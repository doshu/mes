<div class="mailinglistsMembers index">
	<h2><?php echo __('Mailinglists Members'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('member_id'); ?></th>
			<th><?php echo $this->Paginator->sort('mailinglist_id'); ?></th>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($mailinglistsMembers as $mailinglistsMember): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($mailinglistsMember['Member']['id'], array('controller' => 'members', 'action' => 'view', $mailinglistsMember['Member']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($mailinglistsMember['Mailinglist']['name'], array('controller' => 'mailinglists', 'action' => 'view', $mailinglistsMember['Mailinglist']['id'])); ?>
		</td>
		<td><?php echo h($mailinglistsMember['MailinglistsMember']['id']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $mailinglistsMember['MailinglistsMember']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $mailinglistsMember['MailinglistsMember']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $mailinglistsMember['MailinglistsMember']['id']), null, __('Are you sure you want to delete # %s?', $mailinglistsMember['MailinglistsMember']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Mailinglists Member'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Members'), array('controller' => 'members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Member'), array('controller' => 'members', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
	</ul>
</div>
