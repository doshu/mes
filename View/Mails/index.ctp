<?php $this->set('title_for_layout', __('Gestione Email')); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione Email'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-envelope"></i></span><span class="text">'.__('Nuova Email').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Nuova Email')
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="mailStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count['allmail'];?></h2>
				<h5><?php echo __('Email in archivio'); ?></h5>
				<div class="stat-icon"><i class="fa fa-envelope"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count['allsending'];?></h2>
				<h5><?php echo __('Invii effettuati'); ?></h5>
				<div class="stat-icon"><i class="fa fa-plane"></i></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Email in archivio'); ?>
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
										'onclick' => "$('#MailIndexForm').submit();" 
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
					<div class="grid-toolbar">
						<?php echo $this->element('pager'); ?>
					</div>
					<div class="grid-toolbar grid-helper clearfix" data-table="MailGrid">
						<?php echo $this->element('selector_helper'); ?>
						<?php 
						
							echo $this->Form->create(
								'Member', 
								array('style' => 'display:inline', 'url' => array('action' => 'bulk'), 'id' => 'MailIndexActionForm')
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
					<?php echo $this->Form->create('Mail', array('class' => 'form-inline')); ?>
					<table id="MailGrid" class="table table-striped table-bordered table-hover interactive table-centered">
						<?php if(empty($mails)) : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessuna Email trovata'); ?></h4></td>
							</tr>
						<?php else : ?>
							<thead>
								<tr class="search">
									<th></th>
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
												'created.from', 
												array(
													'label' => false, 
													'placeholder' => __('Cerca per data crazione (da)'),
													'class' => 'form-control input-sm datepicker'
												)
											);
											echo $this->Form->input(
												'created.to', 
												array(
													'label' => false, 
													'placeholder' => __('Cerca per data crazione (a)'),
													'class' => 'form-control input-sm datepicker'
												)
											);
										?>
									</th>
								</tr>
								<tr>
									<th></th>
									<th><?php echo $this->Paginator->sort('name', __('Nome')); ?></th>
									<th><?php echo $this->Paginator->sort('description', __('Descrizione')); ?></th>
									<th><?php echo $this->Paginator->sort('created', __('Data creazione')); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($mails as $mail): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $mail['Mail']['id']));?>">
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
													'id' => false
												)
											);
											
										?>
									</td>
									<td><?php echo h($mail['Mail']['name']); ?></td>
									<td><?php echo h($mail['Mail']['description']); ?></td>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $mail['Mail']['created']);
											echo $this->SafeDate->dateForUser($date);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php echo $this->element('pagination'); ?>
				</div>
			</div>
		</div>
	</div>
</div>


