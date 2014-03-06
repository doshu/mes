<?php $this->set('title_for_layout', __("Email inviate dell'invio #%s di %s", $sending['Sending']['id'], h($sending['Mail']['name']))); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb(h($sending['Mail']['name']), '/mails/view/'.$sending['Mail']['id']); ?>
<?php $this->Html->addCrumb('Invio #'.$sending['Sending']['id'], '/sendings/view/'.$sending['Sending']['id']); ?>
<?php $this->Html->addCrumb('Email Inviate', '/recipients/showSended/'.$sending['Sending']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin">
			<?php echo __("Email inviate dell'invio #%s di %s", $sending['Sending']['id'], h($sending['Mail']['name'])); ?>
		</h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
	
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-bug"></i></span><span class="text">'.__('Mostra invii con errori').'</span>',
			array('action' => 'showErrors', $sending['Sending']['id']),
			array(
				'escape' => false,
				'title' => __('Mostra invii con errori'),
				'class' => 'shortcut-link', 
			)
		);
	?>
</div>
<div class="container-fluid"> 
	<div id="mailStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$sendedCount;?></h2>
				<h5><?php echo __('Email inviate'); ?></h5>
				<div class="stat-icon"><i class="fa fa-plane"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$allCount;?></h2>
				<h5><?php echo __('Email in totale'); ?></h5>
				<div class="stat-icon"><i class="fa fa-envelope"></i></div>
			</div>
		</div>
	</div>
	<div class="row">
		<divl class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Email inviate'); ?>
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
										'onclick' => "$('#RecipientShowSendedForm').submit()"
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
					<?php if(!empty($sended)) : ?>
						<div class="grid-toolbar">
							<?php echo $this->element('pager'); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Recipient', array('class' => 'form-inline')); ?>
					<table class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr class="search">
								<th>
									<?php
										echo $this->Form->input(
											'member_email', 
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
											'opened', 
											array(
												'label' => false, 
												'div' => false, 
												'class' => 'form-control input-sm',
												'options' => array(
													'1' => __('Sì'),
													'0' => __('No'), 
												),
												'empty' => __('Cerca per apertura')
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($sended)) : ?>
							<tr>
								<th><?php echo $this->Paginator->sort('member_email', __('Email')); ?></th>
								<th><?php echo $this->Paginator->sort('opened', __('Letta')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($sended)) : ?>
							<tbody>
							<?php foreach ($sended as $recipient): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $recipient['Recipient']['id'], 'sending' =>$sending['Sending']['id'], 'from' => 'sended'));?>">
									<td><?php echo h($recipient['Recipient']['member_email']); ?></td>
									<td><?php echo $recipient['Recipient']['opened']?__('Sì'):__('No'); ?></td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($sended)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessuna email inviata'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>



