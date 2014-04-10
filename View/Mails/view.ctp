<?php $this->set('title_for_layout', h($mail['Mail']['name'])); ?>
<?php $this->set('active', 'email'); ?>
<?php App::uses('Sending', 'Model'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb(h($mail['Mail']['name']), '/mails/view/'.$mail['Mail']['id']); ?>
<?php
	$this->Paginator->options(array(
		'url' => array(
		    'controller' => 'mails',
		    'action' => 'view',
		    $mail['Mail']['id'],
		    '#' => 'sendingTable'
		)
	));
?>
<div class="main-header clearfix">
	<div class="headline">
		<h3><?php echo h($mail['Mail']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-plane"></i></span><span class="text">'.__('Invia Email').'</span>',
			array('controller' => 'sendings', 'action' => 'add', $mail['Mail']['id']),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Invia Email')
			)
		);
		
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Email').'</span>',
			array('action' => 'edit', $mail['Mail']['id']),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Modifica Email')
			)
		);
		
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Email').'</span>',
			array('action' => 'delete', $mail['Mail']['id']),
			array(
				'class' => 'shortcut-link',
				'data-to-confirm' => __("Sei sicuro di voler eliminare l'Email?"),
				'escape' => false,
				'title' => __('Elimina Email')
			)
		);
	?>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Email'); ?>
				</div>
				<div class="panel-body table-overflow">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome'); ?></span>
								</td>
								<td>
									<?php echo h($mail['Mail']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Descrizione'); ?></span>
								</td>
								<td>
									<?php echo h($mail['Mail']['description']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Oggetto'); ?></span>
								</td>
								<td>
									<?php echo h($mail['Mail']['subject']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Punteggio Spam HTML'); ?></span>
								</td>
								<td>
									<?php 
										if($mail['Mail']['html_spam_point'] == null)
											$mail['Mail']['html_spam_point'] = 0;
										
										$spamClass = 'danger';
										if($mail['Mail']['html_spam_point'] <= SPAM_LIMIT_WARNING)
											$spamClass = 'warning';
										if($mail['Mail']['html_spam_point'] <= SPAM_LIMIT_OK)
											$spamClass = 'success';
										
										echo '<span class="label label-'.$spamClass.'">'.h($mail['Mail']['html_spam_point']).'%'.'</span>'; 
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Punteggio Spam Testo'); ?></span>
								</td>
								<td>
									<?php 
										if($mail['Mail']['text_spam_point'] == null)
											$mail['Mail']['text_spam_point'] = 0;
										
										$spamClass = 'danger';
										if($mail['Mail']['text_spam_point'] <= SPAM_LIMIT_WARNING)
											$spamClass = 'warning';
										if($mail['Mail']['text_spam_point'] <= SPAM_LIMIT_OK)
											$spamClass = 'success';
											
										echo '<span class="label label-'.$spamClass.'">'.h($mail['Mail']['text_spam_point']).'%'.'</span>'; 
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $mail['Mail']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Formato HTML'); ?></span>
								</td>
								<td>
									<?php
										echo $this->Html->link(
											'<i class="fa fa-picture-o"></i>', 
											array('action' => 'preview', $mail['Mail']['id']),
											array(
												'escape' => false,
												'data-toggle' => 'tooltip',
												'data-placement' => 'right',
												'data-container' => 'body',
												'title' => __('Anteprima', true),
												'target' => '_blank'
											)
										);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Formato Testo'); ?></span>
								</td>
								<td>
									<?php echo h($mail['Mail']['text']); ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Allegati Email'); ?>
				</div>
				<div class="panel-body table-overflow">
					<?php if(!empty($mail['Attachment'])) : ?>
						<table class="table table-striped">
							<tbody>
								<?php foreach($mail['Attachment'] as $attachment) : ?>
									<tr>
										<td><?php echo h($attachment['realname']); ?></td>
										<td>
											<?php 
												echo $this->Html->link(
													'<i class="fa fa-hdd-o"></i>',
													array('controller' => 'attachments', 'action' => 'download', $attachment['id']),
													array(
														'escape' => false,
														'data-toggle' => 'tooltip',
														'data-placement' => 'right',
														'title' => __('Download')
													)
												);
											?>
										</td>
									<tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else : ?>
						<h4 style="text-align:center;"><?php echo __('Non ci sono allegati'); ?></h4>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default" id="sendingTable">
				<div class="panel-heading">
					<?php echo __('Invii Effettuati'); ?>
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
										'onclick' => "$('#SendingViewForm').submit();" 
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
				<div class="panel-body">
					<?php if(!empty($sendings)) : ?>
						<div class="grid-toolbar">
							<?php echo $this->element('pager'); ?>
						</div>
						<div class="grid-toolbar grid-helper clearfix" data-table="SendingGrid">
							<?php 
						
								echo $this->Form->create(
									'Sending', 
									array(
										'style' => 'display:inline', 
										'url' => array('controller' => 'sendings', 'action' => 'bulk'), 
										'id' => 'MailViewActionForm',
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
											'id' => 'MailViewActionFormSubmit'
										)
									);
								?>
							</div>
						
							<?php echo $this->Form->end(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->Form->create('Sending', array('class' => 'form-inline table-overflow')); ?>
					<table id="SendingGrid" class="table table-striped interactive table-centered table-hover table-bordered">
						<thead>
							<tr class="search">
								<th></th>
								<th>
									<?php
										echo $this->Form->input(
											'Sending.created.from', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data invio (da)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
										echo $this->Form->input(
											'Sending.created.to', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per data invio (a)'),
												'class' => 'form-control input-sm datepicker'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'note', 
											array(
												'label' => false, 
												'div' => false, 
												'type' => 'text',
												'placeholder' => __('Cerca per note'),
												'class' => 'form-control input-sm'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'Sending.recipient_count.from', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per n° destinatari (da)'),
												'class' => 'form-control input-sm'
											)
										);
										echo $this->Form->input(
											'Sending.recipient_count.to', 
											array(
												'label' => false, 
												'placeholder' => __('Cerca per n° destinatari (a)'),
												'class' => 'form-control input-sm'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'smtp_email', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per indirizzo di invio'),
												'class' => 'form-control input-sm',
												'type' => 'text'
											)
										);
									?>
								</th>
								<th>
									<?php
										echo $this->Form->input(
											'status', 
											array(
												'label' => false, 
												'div' => false, 
												'placeholder' => __('Cerca per stato'),
												'class' => 'form-control input-sm',
												'options' => array(
													Sending::$WAITING => __('In Attesa'),
													Sending::$SENDING => __('In Corso'),
													Sending::$COMPLETED => __('Completato'),
													Sending::$ABORTED => __('Annullato')
												),
												'empty' => __('Cerca per stato'),
											)
										);
									?>
								</th>
							</tr>
							<?php if(!empty($sendings)) : ?>
							<tr>
								<th></th>
								<th><?php echo $this->Paginator->sort('created', __('Data Invio')); ?></th>
								<th><?php echo $this->Paginator->sort('note', __('Note')); ?></th>
								<th><?php echo $this->Paginator->sort('recipient_count', __('Destinatari')); ?></th>
								<th><?php echo $this->Paginator->sort('smtp_email', __('Inviato da')); ?></th>
								<th><?php echo $this->Paginator->sort('status', __('Stato')); ?></th>
							</tr>
							<?php endif; ?>
						</thead>
						<?php if(!empty($sendings)) : ?>
							<tbody>
							<?php foreach($sendings as $sending) : ?>
								<tr data-url="<?php echo $this->Html->url(array('controller' => 'sendings', 'action' => 'view', $sending['Sending']['id'])); ?>">
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
													'value' => $sending['Sending']['id']
												)
											);
											
										?>
									</td>
									<td>
										<?php 
											if(empty($sending['Sending']['time'])) {
												$date = DateTime::createFromFormat('Y-m-d H:i:s', $sending['Sending']['created']); 
											}
											else {
												$date = new DateTime();
												$date->setTimestamp($sending['Sending']['time']);
											}
											echo $this->SafeDate->dateForUser($date);
										?>
									</td>
									<td><?php echo h($sending['Sending']['note']); ?></td>
									<td>
										<?php 
											echo $sending['Sending']['recipient_count']; 
										?>
									</td>
									<td><?php echo $sending['Sending']['smtp_email']; ?></td>
									<td>
										<?php 
											switch($sending['Sending']['status']) {
												case Sending::$WAITING:
													$status = __('In attesa');
												break;
												case Sending::$SENDING:
													$status = __('In corso');
												break;
												case Sending::$COMPLETED:
													$status = __('Completato');
												break;
												case Sending::$ABORTED:
													$status = __('Annullato');
												break;
												default:
													$status = __('');
											} 
											if($sending['Sending']['errors'])
												$status = sprintf('<span class="error-message"><i class="fa fa-warning"></i>%s</span>', $status);
											echo $status;
										?>
									</td>
								</tr>
							<?php endforeach; ?>
							</tbody>
						<?php endif; ?>
					</table>
					<?php echo $this->Form->end(); ?>
					<?php if(empty($sendings)) : ?>
						<div><h4 style="text-align:center;"><?php echo __('Nessun invio effettuato'); ?></h4></div>
					<?php else: ?>
						<?php echo $this->element('pagination'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php //debug($mail); ?>
</div>
