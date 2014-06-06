<?php $this->set('title_for_layout', __('Gestione API')); ?>
<?php $this->Html->addCrumb('Gestione API', '/apis/index'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione API'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<ul class="nav nav-tabs" id="settingsNav">
		  <li><?php echo $this->Html->link(__('Gestione utente'), array('controller' => 'users', 'action' => 'settings')); ?></li>
		  <li class="active"><?php echo $this->Html->link(__('Gestione API'), array('controller' => 'apis', 'action' => 'index')); ?></li>
		</ul>
	</div>
</div>

<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-plus-circle"></i></span><span class="text">'.__('Nuova Chiave API').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Nuova Chiave API'),
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="mailStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=count($keys);?></h2>
				<h5><?php echo __('Chiavi API'); ?></h5>
				<div class="stat-icon"><i class="fa fa-key"></i></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Chiavi API'); ?>
				</div>
				<div class="panel-body">
					<span class="pull-right">
						<div class="btn-group">
							<?php
								echo $this->Form->button(
									__('Cerca', true), 
									array(
										'label' => false, 
										'div' => false, 
										'class' => 'btn btn-primary btn-sm',
										'type' => 'button',
										'onclick' => "$('#ApiIndexForm').submit();" 
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
				</div>
				<div class="panel-body nopadding">
					
					<?php if(!empty($keys)) : ?>
						<div class="grid-toolbar">
							<?php echo $this->element('pager'); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Api', array('class' => 'form-inline table-overflow')); ?>
					<table id="ApiGrid" class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr class="search">
								<th>
									<?php
										echo $this->Form->input(
											'name', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per nome'),
												'class' => 'form-control input-sm'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'description', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per descrizione'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'clientkey', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per chiave client'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'Api.created.from', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data crazione (da)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
										echo $this->Form->input(
											'Api.created.to', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data crazione (a)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($keys)) : ?>
							<tr>
								<th><?php echo $this->Paginator->sort('name', __('Nome')); ?></th>
								<th><?php echo $this->Paginator->sort('description', __('Descrizione')); ?></th>
								<th><?php echo $this->Paginator->sort('clientkey', __('Chiave Client')); ?></th>
								<th><?php echo $this->Paginator->sort('created', __('Data creazione')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($keys)) : ?>
							<tbody>
							<?php foreach ($keys as $key): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $key['Api']['id']));?>">
									<td><?php echo h($key['Api']['name']); ?></td>
									<td><?php echo h($key['Api']['description']); ?></td>
									<td><?php echo h($key['Api']['clientkey']); ?></td>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $key['Api']['created']);
											echo $this->SafeDate->dateForUser($date);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($keys)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessuna Chiave API'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


