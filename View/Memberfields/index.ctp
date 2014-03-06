<?php $this->set('title_for_layout', __('Gestione Campi Membri')); ?>
<?php $this->set('active', 'member'); ?>
<?php //debug($mailinglists); exit; ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Gestione Campi Membri'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-list"></i></span><span class="text">'.__('Nuovo Campo').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Indirizz'),
			)
		);
	?>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Campi Membri'); ?>
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
										'onclick' => "$('#MemberfieldIndexForm').submit();"
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
				<div class="widget-content nopadding">
					<?php echo $this->Form->create('Memberfield', array('class' => 'form-inline')); ?>
					<table class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr class="search">
								<th>
									<?php echo $this->Form->input(
										'email', 
										array(
											'label' => false, 
											'div' => false,
											'type' => 'text',
											'placeholder' => __('Cerca per email'),
											'class' => 'form-control input-sm',
											'required' => false
										)
									); ?>
								</th>
								<th>
									<?php echo $this->Form->input(
										'code', 
										array(
											'label' => false, 
											'div' => false,
											'type' => 'text',
											'placeholder' => __('Cerca per codice'),
											'class' => 'form-control input-sm',
											'required' => false
										)
									); ?>
								</th>
								<th>
									<?php echo $this->Form->input(
										'type', 
										array(
											'label' => false, 
											'div' => false,
											'class' => 'form-control input-sm',
											'required' => false,
											'options' => array(
												'0' => __('Campo testo'),
												'1' => __('Area di testo'),
												'2' => __('Sì/No'),
												'3' => __('Data'),
											),
											'empty' => __('Cerca per tipo')
										)
									); ?>
								</th>
							</tr>
							<?php if(!empty($memberfields)) : ?>
							<tr>
								<th><?php echo $this->Paginator->sort('name', __('Nome')); ?></th>
								<th><?php echo $this->Paginator->sort('code', __('Codice')); ?></th>
								<th><?php echo $this->Paginator->sort('type', __('Tipo')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($memberfields)) : ?>
							<tbody>
							<?php foreach ($memberfields as $memberfield): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $memberfield['Memberfield']['id']));?>">
									<td><?php echo h($memberfield['Memberfield']['name']); ?></td>
									<td><?php echo h($memberfield['Memberfield']['code']); ?></td>
									<td>
										<?php
											$typeLabel = '';
											switch($memberfield['Memberfield']['type']) {
												case 0:
													$typeLabel = 'Campo testo';
												break;
												case 1:
													$typeLabel = 'Area di testo';
												break;
												case 2:
													$typeLabel = 'Sì/No';
												break;
												case 3:
													$typeLabel = 'Data';
												break;
											}
											echo $typeLabel;
										?>
								
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($memberfields)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessuna Campo Membro trovato'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


