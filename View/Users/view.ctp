<div class="users view">
<h2><?php  echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Surname'); ?></dt>
		<dd>
			<?php echo h($user['User']['surname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Smtp Host'); ?></dt>
		<dd>
			<?php echo h($user['User']['smtp_host']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Smtp Port'); ?></dt>
		<dd>
			<?php echo h($user['User']['smtp_port']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Smtp Enctype'); ?></dt>
		<dd>
			<?php echo h($user['User']['smtp_enctype']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Smtp Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['smtp_username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Smtp Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['smtp_password']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mailinglists'), array('controller' => 'mailinglists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Mails'), array('controller' => 'mails', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Mail'), array('controller' => 'mails', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Mailinglists'); ?></h3>
	<?php if (!empty($user['Mailinglist'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Mailinglist'] as $mailinglist): ?>
		<tr>
			<td><?php echo $mailinglist['id']; ?></td>
			<td><?php echo $mailinglist['user_id']; ?></td>
			<td><?php echo $mailinglist['name']; ?></td>
			<td><?php echo $mailinglist['description']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'mailinglists', 'action' => 'view', $mailinglist['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'mailinglists', 'action' => 'edit', $mailinglist['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'mailinglists', 'action' => 'delete', $mailinglist['id']), null, __('Are you sure you want to delete # %s?', $mailinglist['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Mailinglist'), array('controller' => 'mailinglists', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Mails'); ?></h3>
	<?php if (!empty($user['Mail'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Html'); ?></th>
		<th><?php echo __('Text'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($user['Mail'] as $mail): ?>
		<tr>
			<td><?php echo $mail['id']; ?></td>
			<td><?php echo $mail['user_id']; ?></td>
			<td><?php echo $mail['name']; ?></td>
			<td><?php echo $mail['description']; ?></td>
			<td><?php echo $mail['html']; ?></td>
			<td><?php echo $mail['text']; ?></td>
			<td><?php echo $mail['created']; ?></td>
			<td><?php echo $mail['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'mails', 'action' => 'view', $mail['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'mails', 'action' => 'edit', $mail['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'mails', 'action' => 'delete', $mail['id']), null, __('Are you sure you want to delete # %s?', $mail['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Mail'), array('controller' => 'mails', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
