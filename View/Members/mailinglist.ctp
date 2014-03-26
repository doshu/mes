<?php $this->set('title_for_layout', __('Membri della lista').' '.h($mailinglist['Mailinglist']['name'])); ?>
<?php $this->set('active', 'list'); ?>
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
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-thumbs-o-up"></i></span><span class="text">'.__('Valida Indirizzi').'</span>',
			array(
				'controller' => 'mailinglists',
				'action' => 'validateListAddresses', 
				$mailinglist['Mailinglist']['id']
			),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Valida Indirizzi'),
			)
		);
	?>
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
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$countValid;?></h2>
				<h5><?php echo __('Indirizzi Validi'); ?></h5>
				<div class="stat-icon"><i class="fa fa-thumbs-o-up"></i></div>
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
						array('url' => array('action' => 'addQuick', 'ext' => 'json'), 
						'class' => 'form-inline',
						'id' => 'MemberAddQuickForm'
						)); 
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
										'onclick' => "$('#MemberMailinglistForm').submit();"
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
					<?php if(!empty($members)) : ?>
					<div class="grid-toolbar grid-helper clearfix" data-table="MemberGrid">
						<?php 
						
							echo $this->Form->create(
								'Member', 
								array(
									'style' => 'display:inline', 
									'url' => array(
										'action' => 'bulk', 
										'from' => 'mailinglists', 
										'scope' => $mailinglist['Mailinglist']['id']
									), 
									'id' => 'MemberMailinglistActionForm'
								)
							); 
						?>
						<?php echo $this->element('selector_helper'); ?>
						<?php echo $this->Form->hidden('mailinglist', array('value' => $mailinglist['Mailinglist']['id'])); ?>
						<div class="action-container">
							<span><?=__('Azioni');?> </span>
							<?php 
								echo $this->Form->input(
									'action', 
									array(
										'options' => array(
											'bulkValidate' => __('Valida Indirizzi'),
											'bulkDelete' => __('Elimina'),
											'bulkUnsubscribe' => __('Disiscrivi')
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
										'id' => 'MemberMailinglistActionFormSubmit'
									)
								);
							?>
						</div>
						
						<?php echo $this->Form->end(); ?>
					</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Member', array('class' => 'form-inline')); ?>
					<table id="MemberGrid" class="table table-striped table-bordered table-hover interactive table-centered">
						<thead>
							<tr class="search">
								<th></th>
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
								<?php
									foreach($memberAdditionalFields as $additionalField) {
										if($additionalField['Memberfield']['in_grid']) :
								?>
								<th>
									<?php
										if(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'date') {
											echo $this->Form->input(
												'Member.'.$additionalField['Memberfield']['code'].'.from', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per ').$additionalField['Memberfield']['name'].__(' (da)'),
													'class' => 'form-control input-sm datepicker',
													'required' => false
												)
											);
											echo $this->Form->input(
												'Member.'.$additionalField['Memberfield']['code'].'.to', 
												array(
													'label' => false, 
													'type' => 'text',
													'placeholder' => __('Cerca per ').$additionalField['Memberfield']['name'].__(' (a)'),
													'class' => 'form-control input-sm datepicker',
													'required' => false
												)
											);
										}
										elseif(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'boolean') {
											echo $this->Form->input(
												'Member.'.$additionalField['Memberfield']['code'], 
												array(
													'label' => false, 
													'div' => false,
													'class' => 'form-control input-sm',
													'required' => false,
													'empty' => __('Cerca per ').$additionalField['Memberfield']['name'],
													'options' => array(
														'0' => __('No'),
														'1' => __('Sì')
													)
												)
											);
										}
										else {
											echo $this->Form->input(
												'Member.'.$additionalField['Memberfield']['code'], 
												array(
													'label' => false, 
													'div' => false,
													'type' => 'text',
													'placeholder' => __('Cerca per ').$additionalField['Memberfield']['name'],
													'class' => 'form-control input-sm',
													'required' => false
												)
											);
										}
									?>
								</th>
								<?php
										endif;
									}
								?>
								<th>
									<?php
										echo $this->Form->input(
											'Member.created.from', 
											array(
												'label' => false, 
												'type' => 'text',
												'placeholder' => __('Cerca per data creazione (da)'),
												'class' => 'form-control input-sm datepicker',
												'required' => false
											)
										);
										echo $this->Form->input(
											'Member.created.to', 
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
											'Member.valid', 
											array(
												'label' => false, 
												'div' => false,
												'class' => 'form-control input-sm',
												'required' => false,
												'empty' => __('Cerca per validità'),
												'options' => array(
													'0' => __('Non Esiste'),
													'1' => __('Esiste'),
													'2' => __('Impossibile da verificare'),
													'-1' => __('Non verificato')
												)
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($members)) : ?>
							<tr>
								<th></th>
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
								<th><?php echo $this->Paginator->sort('valid', __('Validità')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($members)) : ?>
							<tbody>
							<?php foreach ($members as $member): ?>
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $member['Member']['id'], 'from' =>  $mailinglist['Mailinglist']['id']));?>">
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
													'value' => $member['Member']['id']
												)
											);
											
										?>
									</td>
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
											elseif(Memberfield::$dataType[$additionalField['Memberfield']['type']] == 'boolean') {
												echo $member['Member'][$additionalField['Memberfield']['code']]?__('Sì'):('No');
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
									<td>
										<?php
											switch($member['Member']['valid']) {
												case Member::isNotValid:
													echo '<span class="label label-danger">'.__('Non Esiste').'</span>';
												break;
												case Member::isValid:
													echo '<span class="label label-success">'.__('Esiste').'</span>';
												break;
												case Member::cannotValidate:
													echo '<span class="label label-warning">'.__('Impossibile Verificare').'</span>';
												break;
												default:
													echo '<span class="label label-default">'.__('Non Verificato').'</span>';
											}
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($members)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessuna Membro trovato'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>


