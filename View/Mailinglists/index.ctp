<?php $this->set('title_for_layout', __('Gestione Liste')); ?>
<?php $this->set('active', 'list'); ?>
<?php //debug($mailinglists); exit; ?>
<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione Liste'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-group"></i></span><span class="text">'.__('Nuova Lista').'</span>',
			array('action' => 'add'), 
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Nuova Lista'),
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="listStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Liste disponibili'); ?></h5>
				<div class="stat-icon"><i class="fa fa-group"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$avg;?></h2>
				<h5><?php echo __('Media Membri/Lista'); ?></h5>
				<div class="stat-icon"><i class="fa fa-tachometer"></i></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Liste'); ?>
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
										'type' => 'button',
										'onclick' => "$('#MailinglistIndexForm').submit();" 
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
										'class' => 'btn btn-sm btn-default',
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
					<div class="grid-toolbar grid-helper clearfix" data-table="MailinglistGrid">
						<?php echo $this->element('selector_helper'); ?>
						<?php 
						
							echo $this->Form->create(
								'Member', 
								array('style' => 'display:inline', 'url' => array('action' => 'bulk'), 'id' => 'MailinglistIndexActionForm')
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
					<?php echo $this->Form->create('Mailinglist', array('class' => 'form-inline')); ?>
					<table id="MailinglistGrid" class="table table-striped table-bordered table-hover interactive table-centered">
						<?php if(empty($mailinglists)) : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessuna Lista trovata'); ?></h4></td>
							</tr>
						<?php else : ?>
							<thead>
								<tr class="search">
									<th></th>
									<th>
										<?php echo $this->Form->input(
											'name', 
											array(
												'label' => false, 
												'div' => false,
												'type' => 'text',
												'placeholder' => __('Cerca per nome'),
												'class' => 'form-control input-sm',
												'required' => false
											)
										); ?>
									</th>
									<th>
										<?php echo $this->Form->input(
											'description', 
											array(
												'label' => false, 
												'div' => false,
												'type' => 'text',
												'placeholder' => __('Cerca per descrizione'),
												'class' => 'form-control input-sm',
												'required' => false
											)
										); ?>
									</th>
									<th>
										<?php
											echo $this->Form->input(
												'Mailinglist.created.from', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per data creazione (da)'),
													'class' => 'form-control input-sm datepicker',
													'required' => false
												)
											);
											echo $this->Form->input(
												'Mailinglist.created.to', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per data creazione (a)'),
													'class' => 'form-control input-sm datepicker',
													'required' => false
												)
											);
										?>
									</th>
									<th>
										<?php
											echo $this->Form->input(
												'Mailinglist.members_count.from', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per n° membri (da)'),
													'class' => 'form-control input-sm',
													'required' => false
												)
											);
											echo $this->Form->input(
												'Mailinglist.members_count.to', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per n° membri (a)'),
													'class' => 'form-control input-sm',
													'required' => false
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
									<th><?php echo $this->Paginator->sort('members_count', __('Numero Membri')); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($mailinglists as $mailinglist): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $mailinglist['Mailinglist']['id']));?>">
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
									<td><?php echo h($mailinglist['Mailinglist']['name']); ?></td>
									<td><?php echo h($mailinglist['Mailinglist']['description']); ?></td>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $mailinglist['Mailinglist']['created']);
											echo $this->SafeDate->dateForUser($date);
										?>
									</td>
									<td><?php echo $mailinglist['Mailinglist']['members_count']; ?></td>
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


