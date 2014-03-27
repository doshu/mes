<?php $this->set('title_for_layout', __('Destinatario %s', h($recipient['Recipient']['member_email']))); ?>
<?php $this->set('active', 'email'); ?>
<?php App::uses('Sending', 'Model'); ?>
<?php
	if(isset($this->request->params['named']['sending']) && !empty($this->request->params['named']['sending'])) {
		$Sending = new Sending();
		$sending = $Sending->find(
			'first', 
			array(
				'recursive' => -1, 
				'conditions' => array('Sending.id' => $this->request->params['named']['sending']), 
				'contain' => array('Mail')
			)
		);
	}
	
	if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
		$from =  $this->request->params['named']['from'];
	}
?>
<?php 
	if(isset($from) && isset($sending)) {
		$this->Html->addCrumb('Email', '/mails/index');
		$this->Html->addCrumb(h($sending['Mail']['name']), '/mails/view/'.$sending['Mail']['id']);
		$this->Html->addCrumb('Invio #'.$sending['Sending']['id'], '/sendings/view/'.$sending['Sending']['id']);
		if($from == 'sended') {
			$this->Html->addCrumb('Email Inviate', '/recipients/showSended/'.$sending['Sending']['id']);
		}
		elseif($from == 'opened') {
			$this->Html->addCrumb('Email Lette', '/recipients/showOpened/'.$sending['Sending']['id']);
		}
		elseif($from == 'errors') {
			$this->Html->addCrumb('Email con Errori', '/recipients/showErrors/'.$sending['Sending']['id']);
		}
		$this->Html->addCrumb(h($recipient['Recipient']['member_email']), '/recipients/view/'.$recipient['Recipient']['id']);
	}
?>
<?php 
	App::uses('Member', 'Model'); 
	$MemberModel = new Member();
?>

<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Destinatario %s', h($recipient['Recipient']['member_email'])); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo __("Dettagli dell'invio"); ?>
					</div>
					<div class="panel-body nopadding">
						<table class="table table-striped">
							<tbody>
								<tr>
									<td>
										<span class="custom-label"><?php echo __('Email'); ?></span>
									</td>
									<td>
										<?php 
											if($MemberModel->exists($recipient['Recipient']['member_id'])) {
												echo $this->Html->link(
													h($recipient['Recipient']['member_email']),
													array('controller' => 'members', 'action' => 'view', $recipient['Recipient']['member_id'])
												);
											}
											else {
												echo h($recipient['Recipient']['member_email']);
											}
										?>
									</td>
								</tr>
								<tr>
									<td>
										<span class="custom-label"><?php echo __('Email inviata'); ?></span>
									</td>
									<td>
										<?php echo $recipient['Recipient']['sended']?__('Sì'):__('No'); ?>
									</td>
								</tr>
								<?php if($recipient['Recipient']['sended_time']) : ?>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Data e ora Invio'); ?></span>
										</td>
										<td>
											<?php
												$sended_time = new DateTime();
												$sended_time->setTimestamp($recipient['Recipient']['sended_time']);
												echo $this->SafeDate->dateForUser($sended_time);
											?>
										</td>
									</tr>
								<?php endif; ?>
								<?php if($recipient['Recipient']['errors']) : ?>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Errori'); ?></span>
										</td>
										<td>
											<span class="error-message"><?php echo __("Errore durante l'invio"); ?></span>
										</td>
									</tr>
								<?php endif; ?>
								<tr>
									<td>
										<span class="custom-label"><?php echo __('Letta'); ?></span>
									</td>
									<td>
										<?php echo $recipient['Recipient']['opened']?__('Sì'):__('No'); ?>
									</td>
								</tr>
								<?php if($recipient['Recipient']['opened']) : ?>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Data lettura'); ?></span>
										</td>
										<td>
											<?php 
												$opened_time = new DateTime($recipient['Recipient']['opened_time']); 
												echo $this->SafeDate->dateForUser($opened_time);
											?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Tipo supporto'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['device']); ?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Sistema operativo'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['os']); ?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Browser'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['browser']); ?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Stato'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['country']); ?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Regione'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['region']); ?>
										</td>
									</tr>
									<tr>
										<td>
											<span class="custom-label"><?php echo __('Coordinate'); ?></span>
										</td>
										<td>
											<?php echo h($recipient['Recipient']['lat']).' '.h($recipient['Recipient']['lon']); ?>
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
					</table>
				</div>
			</div>
			<?php if(!empty($recipient['Link'])) : ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo __("Link seguiti dalla Email"); ?>
					</div>
					<div class="panel-body nopadding">
						<table class="table table-striped table-centered table-bordered">
							<thead>
								<tr>
									<th><?php echo __('Url'); ?></th>
									<th><?php echo __('Data e ora apertura'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($recipient['Link'] as $link) : ?>
									<tr>
										<td>
											<?php
												echo $this->Html->link(h($link['url']), $link['url'], array('target' => '_blank'));
											?>
										</td>
										<td>
											<?php
												$openTime = new DateTime($link['date']);
												echo $this->SafeDate->dateForUser($openTime); 
											?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
