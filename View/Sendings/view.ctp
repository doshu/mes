<?php $this->set('title_for_layout', __('Invio di').' '.h($sending['Mail']['name'])); ?>
<?php $this->Html->css('jquery-jvectormap.css', array('inline' => false)); ?>
<?php $this->Html->script('jquery-jvectormap.min.js', array('inline' => false)); ?>
<?php $this->Html->script('jquery-jvectormap-world-mill-en.js', array('inline' => false)); ?>
<?php $this->Html->script('raphael-min', array('inline' => false)); ?>
<?php $this->Html->script('g.raphael-min', array('inline' => false)); ?>
<?php $this->Html->script('g.pie-min', array('inline' => false)); ?>

<?php App::uses('Sending', 'Model'); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb(h($sending['Mail']['name']), '/mails/view/'.$sending['Mail']['id']); ?>
<?php $this->Html->addCrumb('Invio #'.$sending['Sending']['id'], '/sendings/view/'.$sending['Sending']['id']); ?>
<?php
	$this->Javascript->setGlobal(array(
		'statusCode' => array(
			'waiting' => Sending::$WAITING,
			'sending' => Sending::$SENDING,
			'completed' => Sending::$COMPLETED,
			'aborted' => Sending::$ABORTED
		),
		'sending_id' => $sending['Sending']['id']
	));
?>
<?php
	$browserStatsDataset = Hash::extract($browsers, '{n}.Recipient');
	$deviceStatsDataset = Hash::extract($devices, '{n}.Recipient');
	$osStatsDataset = Hash::extract($oss, '{n}.Recipient');
?>

<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Invio di').' '.h($sending['Mail']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
	
		if(
			$sending['Sending']['status'] == Sending::$WAITING || 
			$sending['Sending']['status'] == Sending::$COMPLETED || 
			$sending['Sending']['status'] == Sending::$ABORTED
		) 
		{ 
			echo $this->Form->postLink(
				'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Invio').'</span>',
				array('action' => 'delete', $sending['Sending']['id'], $sending['Mail']['id']),
				array(
					'class' => 'shortcut-link', 
					'data-to-confirm' => __("Sei sicuro di voler eliminare questo invio?"),
					'escape' => false,
					'title' => __('Elimina invio')
				)
			);
		}
		
		if(
			($sending['Sending']['errors'] && $sending['Sending']['status'] == Sending::$COMPLETED) || 
			$sending['Sending']['status'] == Sending::$ABORTED
		) { 
			echo $this->Form->postLink(
				'<span class="shortcut-icon"><i class="fa fa-refresh"></i></span><span class="text">'.__('Ripeti invio per email NON Inviate').'</span>',
				array('action' => 'resend', $sending['Sending']['id']),
				array(
					'class' => 'shortcut-link',
					'data-to-confirm' => __("Sei sicuro di voler tentare il reinvio delle email?"),
					'escape' => false,
					'title' => __('Ripeti invio per email NON Inviate')
				)
			);
		}
		
	?>
</div>
<div class="container-fluid"> 
	<div id="sendingStat" class="site-stats clearfix">
		<div class="col-lg-3">	
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$sending['Sending']['recipient_count'];?></h2>
				<h5><?php echo __('Destinatari'); ?></h5>
				<div class="stat-icon"><i class="fa fa-user"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<a href="<?=$this->Html->url(array('action' => 'unsubscribed', $sending['Sending']['id']));?>">
				<div class="panel-stat3 bg-success">
					<h2 class="m-top-none"><?= (int)$unsubscribed;?></h2>
					<h5><?php echo __('Disiscrizioni'); ?></h5>
					<div class="stat-icon"><i class="fa fa-ban"></i></div>
				</div>
			</a>
		</div>
	</div>
	<div>
		<?php
			$status = "";
			switch($sending['Sending']['status']) {
				case Sending::$WAITING:
					$status = __('In attesa');
					$statusClass = '';
				break;
				case Sending::$SENDING:
					$status = __('In corso');
					$statusClass = 'label-info';
				break;
				case Sending::$COMPLETED:
					$status = __('Completato');
					$statusClass = 'label-success';
				break;
				case Sending::$ABORTED:
					$status = __('Annullato');
					$statusClass = 'label-important';
				break;
				default:
					$status = '';
					$statusClass = '';
			}
		?>
		<p>
			<span class="label <?=$statusClass?>" id="sendingStatus" data-status="<?=$sending['Sending']['status']?>"><?=$status?></span>
		</p>
		<p>
			<div id="sending-error-container">
				<?php if($sending['Sending']['errors']) : ?>
					<span class="label label-danger sending-error"><?=__("Rilevati errori durante l'invio")?></span>
				<?php endif; ?>
			</div>
		</p>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5><?php echo __('Dettagli Invio'); ?></h5>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Email'); ?></span>
								</td>
								<td><?php echo h($sending['Mail']['name']); ?></td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Inviata da'); ?></span>
								</td>
								<td><?php echo h($sending['Smtp']['email']); ?></td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Liste di invio'); ?></span>
								</td>
								<td>
									<?php 
										$linklist = array();
										foreach($mailinglists as $mailinglist) {
											$linklist[] = $this->Html->link($mailinglist['MailinglistsSending']['mailinglist_name'],
												array('controller' => 'mailinglists', 'action' => 'view', $mailinglist['MailinglistsSending']['id'])
											);
										}
										echo implode(', ', $linklist);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome mittente'); ?></span>
								</td>
								<td><?php echo h($sending['Sending']['sender_name']); ?></td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Note'); ?></span>
								</td>
								<td><?php echo h($sending['Sending']['note']); ?></td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Invio come'); ?></span>
								</td>
								<td>
									<?php 
										switch($sending['Sending']['type']) {
											case Sending::$HTML:
												$type = __('HTML');
											break;
											case Sending::$TEXT:
												$type = __('Testo');
											break;
											case Sending::$BOTH:
												$type = __('HTML + Testo');
											break;
											default:
												$type = '';
										} 
										echo $type;
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $sending['Sending']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data invio'); ?></span>
								</td>
								<td>
									<?php 
										if(!empty($sending['Sending']['time'])) {
											$date = new DateTime();
											$date->setTimestamp($sending['Sending']['time']); 
											echo $this->SafeDate->dateForUser($date);
										}
										else
											echo __('Immediato');
									?>
								</td>
							</tr>
							<?php if($sending['Sending']['status'] == Sending::$COMPLETED) : ?>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Durata dell\'invio'); ?></span>
								</td>
								<td>
									<?php
										$elapsed = $sending['Sending']['ended'] - $sending['Sending']['started'];
										echo timeToWords($elapsed);
									?>
								</td>
							</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row" style="margin-bottom:25px;">
		<div class="col-lg-6" style="text-align:center">
			<a href="<?php echo $this->Html->url(array('controller' => 'recipients', 'action' => 'showSended', $sending['Sending']['id'])); ?>" class="graphLink">
				<span id="sendedChart" data-total="<?=$sending['Sending']['recipient_count']?>" data-value="<?=$sended_recipients?>">
				</span>
				<div style="text-align:center"><b><?php echo __('Email inviate'); ?></b></div>
			</a>
		</div>
		<div class="col-lg-6"  style="text-align:center">
			<a href="<?php echo $this->Html->url(array('controller' => 'recipients', 'action' => 'showOpened', $sending['Sending']['id'])); ?>"  class="graphLink">
				<span id="openedChart" data-total="<?=$sending['Sending']['recipient_count']?>" data-value="<?=$opened_recipients?>">
				</span>
				<div style="text-align:center"><b><?php echo __('Email lette'); ?></b></div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5><?php echo __("Link seguiti dalla Email"); ?></h5>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped table-centered table-bordered">
						<?php if(empty($links)) : ?>
							<tbody>
								<tr><td><h4 style="text-align:center;"><?= __('Nessun Link Seguito'); ?></h4></td></tr>
							</tbody>
						<?php else: ?>
							<thead>
								<tr>
									<th><?php echo __('Url'); ?></th>
									<th><?php echo __('Numero di accessi'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($links as $link) : ?>
									<tr>
										<td>
											<?php
												echo $this->Html->link(h($link['Link']['url']), $link['Link']['url'], array('target' => '_blank'));
											?>
										</td>
										<td>
											<?php
												echo $link['Link']['times'];
											?>
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
	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Device più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="deviceStats">
						<?php if(empty($deviceStatsDataset)) : ?>
							<div class="no-data"><?php echo __('Nessun Dato Disponibile'); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Browser più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="browserStats">
						<?php if(empty($browserStatsDataset)) : ?>
							<div class="no-data"><?php echo __('Nessun Dato Disponibile'); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Sistemi operativi più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="osStats">
						<?php if(empty($osStatsDataset)) : ?>
							<div class="no-data"><?php echo __('Nessun Dato Disponibile'); ?></div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">		
			<div class="panel panel-default">
				<div class="panel-heading">
					<h5><?php echo __("Mappa email lette"); ?></h5>
				</div>
				<div class="panel-body nopadding">
					<div id="geoChart" style="height:300px;"></div>
				</div>
			</div>
		</div>
	</div>	
</div>


<?php 
	echo $this->Javascript->setGlobal(array(
		'geoChartDataset' => $geo_data,
	));
?>

<?php 
	echo $this->Javascript->setGlobal(compact(
		'browserStatsDataset',
		'deviceStatsDataset',
		'osStatsDataset'
	));
?>


