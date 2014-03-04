<?php $this->set('title_for_layout', __('Membri della lista').' '.h($mailinglist['Mailinglist']['name'])); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php 
	$this->Phpjs->add('datetime/date'); 
	//echo $this->Html->script('phpjs/datetime/date');
?>
<?php //debug($mailinglists); exit; ?>
<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
<?php $this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglists/view/'.$mailinglist['Mailinglist']['id']); ?>
<?php $this->Html->addCrumb('Membri della Lista', '/members/mailinglist/'.$mailinglist['Mailinglist']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Membri della lista').' '.h($mailinglist['Mailinglist']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">

	<ul id="memberStat" class="site-stats">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Membri in Lista'); ?></h5>
				<div class="stat-icon"><i class="fa fa-group"></i></div>
			</div>
		</div>
	</ul>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Aggiungi Membro rapido'); ?>
				</div>
				<div class="panel-body">
					<?php echo $this->Form->create('Member', 
						array('url' => array('action' => 'addQuick', 'ext' => 'json'), 'class' => 'form-inline')); 
					?>
					<div>
						<?php
				
							echo $this->Form->input(
								'Mailinglist.Mailinglist', 
								array('type' => 'hidden', 'value' => $mailinglist['Mailinglist']['id'])
							);
					
							echo $this->Form->input(
								'email', 
								array('label' => false, 'div' => false, 'placeholder' => __('Inserisci email'), 'class' => 'form-control')
							); 
						?>
						<span id="quickAddResult"></span>
						<?php 
							echo $this->Form->submit(
								__('Salva'), 
								array('class' => 'btn btn-sm btn-primary', 'div' => false, 'style' => 'float:right;')
							); 
						?>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
	
			<div id="newMemberTable" class="panel panel-default hide">
				<div class="panel-heading">
					<?php echo __('Nuovi aggiunti'); ?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr>
								<th><?php echo __('Email'); ?></th>
								<th><?php echo  __('Data creazione'); ?></th>
							</tr>
						</thead>
						<tbody>
				
						</tbody>
					</table>
				</div>
			</div>
	
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5><?php echo __('Membri'); ?></h5>
				</div>
				<div class="panel-body" style="">
					<?php 
						echo $this->Form->create('Member', array('class' => 'form-inline'));
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
						<?php if(empty($members)) : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessun Membro trovato'); ?></h4></td>
							</tr>
						<?php else: ?>
							<thead>
								<tr>
									<th><?php echo $this->Paginator->sort('email', __('Email')); ?></th>
									<?php
										foreach($memberAdditionalFields as $additionalField) {
											if($additionalField['Memberfield']['in_grid']) :
									?>
									<th>
										<?php 
											echo $this->Paginator->sort(
												$additionalField['Memberfield']['code'], 
												ucfirst(__($additionalField['Memberfield']['name']))
											); 
										?>
									</th>
									<?php
											endif;
										}
									?>
									<th><?php echo $this->Paginator->sort('created', __('Data creazione')); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($members as $member): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $member['Member']['id'], 'from' =>  $mailinglist['Mailinglist']['id']));?>">
									<td><?php echo h($member['Member']['email']); ?></td>
									<?php
										foreach($memberAdditionalFields as $additionalField) {
											if($additionalField['Memberfield']['in_grid']) :
									?>
									<td>
										<?php
											if(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'date') {
												$date = DateTime::createFromFormat('Y-m-d', $member['Member'][$additionalField['Memberfield']['code']]);
												echo $this->SafeDate->dateForUser($date, 'd/m/Y');
											}
											else {
												echo h($member['Member'][$additionalField['Memberfield']['code']]);
											}
										?>
									</td>
									<?php
											endif;
										}
									?>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $member['Member']['created']);
											echo $this->SafeDate->dateForUser($date);
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


