<div class="users index">
	<h2><?php echo __('Users'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('surname'); ?></th>
			<th><?php echo $this->Paginator->sort('username'); ?></th>
			<th><?php echo $this->Paginator->sort('password'); ?></th>
			<th><?php echo $this->Paginator->sort('smtp_host'); ?></th>
			<th><?php echo $this->Paginator->sort('smtp_port'); ?></th>
			<th><?php echo $this->Paginator->sort('smtp_enctype'); ?></th>
			<th><?php echo $this->Paginator->sort('smtp_username'); ?></th>
			<th><?php echo $this->Paginator->sort('smtp_password'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['id']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['surname']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['password']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['smtp_host']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['smtp_port']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['smtp_enctype']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['smtp_username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['smtp_password']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $user['User']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $user['User']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mails'), array('controller' => 'mails', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mail'), array('controller' => 'mails', 'action' => 'add')); ?> </li>
	</ul>
</div>
