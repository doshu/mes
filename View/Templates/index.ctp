<?php $this->set('title_for_layout', __('Gestione Template')); ?>
<?php $this->set('active', 'template'); ?>
<?php $this->Html->addCrumb('Template', '/templates/index'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione Template'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-clipboard"></i></span><span class="text">'.__('Nuovo Template').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Nuovo Template')
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="templateStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Template in archivio'); ?></h5>
				<div class="stat-icon"><i class="fa fa-clipboard"></i></div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Template in archivio'); ?>
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
										'onclick' => "$('#TemplateIndexForm').submit();" 
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
					
					<?php if(!empty($templates)) : ?>
						<div class="grid-toolbar">
							<?php echo $this->element('pager'); ?>
						</div>
						<div class="grid-toolbar grid-helper clearfix" data-table="TemplateGrid">
							<?php 
						
								echo $this->Form->create(
									'Template', 
									array(
										'style' => 'display:inline', 
										'url' => array('action' => 'bulk'), 
										'id' => 'TemplateIndexActionForm',
										'class' => 'bulk-form'
									)
								); 
							?>
							<?php echo $this->element('selector_helper'); ?>
							<div class="action-container">
								<span><?=__('Azioni');?> </span>
								<?php 
									echo $this->Form->input(
										'action', 
										array(
											'options' => array(
												'bulkDelete' => __('Elimina')
											),
											'label' => false,
											'div' => false,
											'empty' => true,
											'class' => 'action',
										)
									);
									echo $this->Form->button(
										__('Esegui'), 
										array(
											'label' => false, 
											'div' => false, 
											'class' => 'btn btn-primary btn-xs',
											'type' => 'button',
											'id' => 'TemplateIndexActionFormSubmit'
										)
									);
								?>
							</div>
						
							<?php echo $this->Form->end(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Template', array('class' => 'form-inline table-overflow')); ?>
					<table id="TemplateGrid" class="table table-striped table-bordered table-hover interactive table-centered">
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
											'Template.created.from', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data crazione (da)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
										echo $this->Form->input(
											'Template.created.to', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data crazione (a)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($templates)) : ?>
							<tr>
								<th></th>
								<th><?php echo $this->Paginator->sort('name', __('Nome')); ?></th>
								<th><?php echo $this->Paginator->sort('description', __('Descrizione')); ?></th>
								<th><?php echo $this->Paginator->sort('created', __('Data creazione')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($templates)) : ?>
							<tbody>
							<?php foreach ($templates as $template): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $template['Template']['id']));?>">
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
													'value' => $template['Template']['id']
												)
											);
											
										?>
									</td>
									<td><?php echo h($template['Template']['name']); ?></td>
									<td><?php echo h($template['Template']['description']); ?></td>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $template['Template']['created']);
											echo $this->SafeDate->dateForUser($date);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($templates)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessun template in archivio'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


