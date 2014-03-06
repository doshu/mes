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
					<span class="pull-right">
						<div class="btn-group">
							<?php
								echo $this->Form->button(
									__('Cerca', true), 
									array(
										'label' => false, 
										'div' => false, 
										'class' => 'btn btn-primary btn-sm',
										'type' => 'submit',
										'onclick' => "$('#SmtpIndexForm').submit();" 
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
					<?php if(!empty($smtps)) : ?>
						<div class="grid-toolbar">
							<?php echo $this->element('pager'); ?>
						</div>
						<div class="grid-toolbar grid-helper clearfix" data-table="SmtpGrid">
							<?php echo $this->element('selector_helper'); ?>
							<?php 
						
								echo $this->Form->create(
									'Member', 
									array('style' => 'display:inline', 'url' => array('action' => 'bulk'), 'id' => 'SmtpIndexActionForm')
								); 
							?>
							<div class="action-container">
								<span><?=__('Azioni');?> </span>
								<?php 
									echo $this->Form->input(
										'action', 
										array(
											'options' => array(
												'prova'
											),
											'label' => false,
											'div' => false,
											'empty' => true,
											'class' => 'action',
											'id' => false
										)
									);
									echo $this->Form->button(
										__('Esegui'), 
										array(
											'label' => false, 
											'div' => false, 
											'class' => 'btn btn-primary btn-xs',
											'type' => 'submit',
										)
									);
								?>
							</div>
						
							<?php echo $this->Form->end(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Smtp', array('class' => 'form-inline')); ?>
					<table id="SmtpGrid" class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr class="search">
								<th></th>
								<th>
									<?php
										echo $this->Form->input(
											'email', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per email'),
												'class' => 'form-control input-sm'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'name', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per nome'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'host', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per host'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'port', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per porta'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'username', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per username'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($smtps)) : ?>
							<tr>
								<th></th>
								<th><?php echo $this->Paginator->sort('email', __('Email')); ?></th>
								<th><?php echo $this->Paginator->sort('name', __('Name')); ?></th>
								<th><?php echo $this->Paginator->sort('host', __('Host')); ?></th>
								<th><?php echo $this->Paginator->sort('port', __('Port')); ?></th>
								<th><?php echo $this->Paginator->sort('username', __('Username')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($smtps)) : ?>
							<tbody>
							<?php foreach ($smtps as $smtp): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $smtp['Smtp']['id']));?>">
									<td>
										<?php 
											$this->Form->unlockField('id');
											echo $this->Form->input(
												'id.',
												array(
													'type' => 'checkbox', 
													'hiddenField' => false,
													'label' => false,
													'div' => false,
													'class' => 'grid-el-select',
													'id' => false,
													'value' => $smtp['Smtp']['id']
												)
											);
											
										?>
									</td>
									<td><?php echo h($smtp['Smtp']['email']); ?></td>
									<td><?php echo h($smtp['Smtp']['name']); ?></td>
									<td><?php echo h($smtp['Smtp']['host']); ?></td>
									<td><?php echo h($smtp['Smtp']['port']); ?></td>
									<td><?php echo h($smtp['Smtp']['username']); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($smtps)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessun indirizzo di invio trovato'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


