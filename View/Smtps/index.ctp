<?php $this->set('title_for_layout', __('Indirizzi Invio')); ?>
<?php $this->set('active', 'smtp'); ?>
<?php //debug($mailinglists); exit; ?>
<?php $this->Html->addCrumb('Indirizzi Invio', '/smtps/index'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione Indirizzi di Invio'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-exchange"></i></span><span class="text">'.__('Nuovo Indirizzo').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Nuovo Indirizzo')
			)
		);
	?>
</div>
<div class="container-fluid">
	<ul id="smtpStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Indirizzi in archivio'); ?></h5>
				<div class="stat-icon"><i class="fa fa-exchange"></i></div>
			</div>
		</div>
	</ul>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Indirizzi'); ?>
				</div>
				<div class="panel-body" style="">
					<?php 
						echo $this->Form->create('Smtp', array('class' => 'form-inline'));
						echo $this->Form->input(
							'email', 
							array(
								'label' => false, 
								'div' => false,
								'type' => 'text',
								'placeholder' => __('Cerca per email', true),
								'class' => 'form-control'
							)
						);
					?>
					<span class="pull-right">
						<div class="btn-group">
							<?php
								echo $this->Form->button(
									__('Cerca', true), 
									array(
										'label' => false, 
										'div' => false, 
										'class' => 'btn btn-primary btn-sm',
										'type' => 'submit'
									)
								);
							?>
							<?php
								echo $this->Html->link(
									__('Reset', true),
									array(
										'action' => 'filter_reset', 
										md5($this->params['plugin'].$this->params['controller'].$this->params['action'])
									), 
									array(
										'class' => 'btn btn-default btn-sm',
									)
								);
							?>
						</div>
					</span>
					<?php
						echo $this->Form->end();
					?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped table-bordered table-hover interactive table-centered">
						<?php if(!empty($smtps)) : ?>
							<thead>
								<tr>
									<th><?php echo $this->Paginator->sort('email', __('Email')); ?></th>
									<th><?php echo $this->Paginator->sort('email', __('Name')); ?></th>
									<th><?php echo $this->Paginator->sort('email', __('Host')); ?></th>
									<th><?php echo $this->Paginator->sort('email', __('Port')); ?></th>
									<th><?php echo $this->Paginator->sort('email', __('Username')); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($smtps as $smtp): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $smtp['Smtp']['id']));?>">
									<td><?php echo h($smtp['Smtp']['email']); ?></td>
									<td><?php echo h($smtp['Smtp']['name']); ?></td>
									<td><?php echo h($smtp['Smtp']['host']); ?></td>
									<td><?php echo h($smtp['Smtp']['port']); ?></td>
									<td><?php echo h($smtp['Smtp']['username']); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php else : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessun Indirizzo di invio trovato'); ?></h4></td>
							</tr>
						<?php endif; ?>
					</table>
			
					<?php echo $this->element('pagination'); ?>
				</div>
			</div>
		</div>
	</div>
</div>


