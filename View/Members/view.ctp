<?php $this->set('title_for_layout', h($member['Member']['email'])); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php App::uses('Mailinglist', 'Model'); ?>
<?php
	if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
		$Mailinglist = new Mailinglist();
		$mailinglist = $Mailinglist->find(
			'first', 
			array(
				'recursive' => -1, 
				'conditions' => array('id' => $this->request->params['named']['from'])
			)
		);
	}
?>
<?php if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) : ?>
	<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
	<?php $this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglists/view/'.$mailinglist['Mailinglist']['id']); ?>
	<?php $this->Html->addCrumb(h($member['Member']['email']), '/members/view/'.$member['Member']['id']); ?>
<?php else: ?>
	<?php $this->Html->addCrumb('Membri', '/members/index'); ?>
	<?php $this->Html->addCrumb(h($member['Member']['email']), '/members/view/'.$member['Member']['id']); ?>
<?php endif; ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo h($member['Member']['email']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		$deleteAction = array('action' => 'delete', $member['Member']['id']);
		$editAction = array('action' => 'edit', $member['Member']['id']);
		if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
			$deleteAction['from'] = $this->request->params['named']['from'];
			$editAction['from'] = $this->request->params['named']['from'];
		}	
			
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Membro').'</span>',
			$editAction,
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Membro'),
			)
		);
			
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Membro').'</span>',
			$deleteAction,
			array(
				'data-to-confirm' => __('Sei sicuro di voler eliminare il membro?'), 
				'escape' => false,
				'title' => __('Elimina Membro', true),
				'class' => 'shortcut-link', 
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="memberlistStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<a href="<?=$this->Html->url(array('action' => 'unsubscribed', $member['Member']['id']));?>">
				<div class="panel-stat3 bg-success">
					<h2 class="m-top-none"><?= (int)$unsubscribed;?></h2>
					<h5><?php echo __('Disiscrizioni'); ?></h5>
					<div class="stat-icon"><i class="fa fa-ban"></i></div>
				</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Membro'); ?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Email'); ?></span>
								</td>
								<td>
									<?php echo h($member['Member']['email']); ?>
								</td>
							</tr>
							<?php foreach($memberAdditionalFields as $additionalField) : ?>
							<tr>
								<td>
									<span class="custom-label"><?php echo ucfirst(__($additionalField['Memberfield']['name'])); ?></span>
								</td>
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
							</tr>
							<?php endforeach; ?>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $member['Member']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Validità'); ?></span>
								</td>
								<td>
									<?php
										switch($member['Member']['valid']) {
											case 0:
												echo '<span class="label label-danger">'.__('Non Esiste').'</span>';
											break;
											case 1:
												echo '<span class="label label-success">'.__('Esiste').'</span>';
											break;
											case 2:
												echo '<span class="label label-warning">'.__('Impossibile Verificare').'</span>';
											break;
											default:
												echo '<span class="label label-default">'.__('Non Verificato').'</span>';
										}
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5><?php echo __('Liste di appartenenza'); ?></h5>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped interactive table-hover">
						<?php if(empty($member['Mailinglist'])) : ?>
							<tr>
								<td><h4 class="text-center"><?php echo __('Nessuna Lista di appartenenza'); ?></h4></td>
							</tr>
						<?php else: ?>
							<tbody>
								<?php foreach($member['Mailinglist'] as $mailinglist) : ?>
									<tr data-url="<?php echo $this->Html->url(array('controller' => 'mailinglists', 'action' => 'view', $mailinglist['id'])); ?>">
										<td>
											<?php echo h($mailinglist['name']); ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
