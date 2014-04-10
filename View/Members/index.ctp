<?php $this->set('title_for_layout', __('Membri')); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php $this->Html->addCrumb('Membri', '/members/index'); ?>
<div class="main-header clearfix">
	<div class="page-title">
		<h3 class="headline"><?php echo __('Gestione Membri'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
	
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-user"></i></span><span class="text">'.__('Nuovo Membro').'</span>',
			array('action' => 'add'),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Nuova Membro')
			)
		);
		
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-upload"></i></span><span class="text">'.__('Importa Membro').'</span>',
			array('action' => 'import'),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Importa Membri')
			)
		);
		
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-download"></i></span><span class="text">'.__('Esporta Membri').'</span>',
			array('action' => 'export'),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Esporta Membri')
			)
		);
	?>
</div>
<div class="container-fluid">
	<ul id="memberStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Membri in archivio'); ?></h5>
				<div class="stat-icon"><i class="fa fa-user"></i></div>
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
					<?php echo __('Membri'); ?>
				</div>
				<div class="panel-body" style="">
					<span class="pull-right">
						<div class="btn-group">
							<?php
								echo $this->Form->button(
									__('Cerca'), 
									array(
										'label' => false, 
										'div' => false, 
										'class' => 'btn btn-primary btn-sm',
										'type' => 'button',
										'onclick' => "$('#MemberIndexForm').submit();"
									)
								);
							?>
							<?php
								echo $this->Html->link(
									__('Reset'),
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
								array('style' => 'display:inline', 'url' => array('action' => 'bulk'), 'id' => 'MemberIndexActionForm')
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
											'bulkValidate' => __('Valida Indirizzi'),
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
										'id' => 'MemberIndexActionFormSubmit'
									)
								);
							?>
						</div>
						
						<?php echo $this->Form->end(); ?>
					</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Member', array('class' => 'form-inline table-overflow')); ?>
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
								<tr data-url="<?=$this->Html->url(array('action' => 'view', $member['Member']['id']));?>">
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
											$member['Member']['valid'] = $member['Member']['valid'] === null?-1:$member['Member']['valid'];
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


