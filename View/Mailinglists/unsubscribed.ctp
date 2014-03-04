<?php $this->set('title_for_layout', __("Disiscrizioni della lista", $mailinglist['Mailinglist']['name'])); ?>
<?php $this->set('active', 'list'); ?>
<?php $this->Html->addCrumb('Liste', '/mailinglist/index'); ?>
<?php $this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglist/view/'.$mailinglist['Mailinglist']['id']); ?>
<?php $this->Html->addCrumb('Disiscrizioni', '/mailinglists/unsubscribed/'.$mailinglist['Mailinglist']['id']); ?>
<div class="main-header clearfix">
	<div class="page-title">
		<h3 class="headline">
			<?php echo __("Disiscrizioni della lista", $mailinglist['Mailinglist']['name']); ?>
		</h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid"> 
	<div class="row">
		<divl class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Disiscrizioni'); ?>
				</div>
				<div class="panel-body" style="">
					<?php 
						echo $this->Form->create('Unsubscription', array('class' => 'form-inline'));
						echo $this->Form->input(
							'member_email', 
							array(
								'label' => false, 
								'div' => false, 
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
						<?php if(empty($unsubscribeds)) : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessun Membro disiscritto'); ?></h4></td>
							</tr>
						<?php else : ?>
							<thead>
								<tr>
									<th><?php echo $this->Paginator->sort('member_email', __('Email')); ?></th>
									<th><?php echo $this->Paginator->sort('created', __('Disiscritto il')); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($unsubscribeds as $unsubscribed): ?>
								<tr data-url="<?=$this->Html->url(array('controller' => 'unsubscriptions', 'action' => 'view', $unsubscribed['Unsubscription']['id'], 'from' => 'mailinglists', 'mailinglist' => $mailinglist['Mailinglist']['id']));?>">
									<td><?php echo h($unsubscribed['Unsubscription']['member_email']); ?></td>
									<td>
										<?php 
											$created = new DateTime($unsubscribed['Unsubscription']['created']);
											if($created instanceof DateTime) {
												echo $this->SafeDate->dateForUser($created);
											}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->element('pagination'); ?>
				</div>
			</div>
		</div>
	</div>
</div>

