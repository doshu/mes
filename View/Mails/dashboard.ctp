
<?php $this->Html->script('raphael-min', array('inline' => false)); ?>

<?php $this->set('title_for_layout', __('Dashboard')); ?>
<?php $this->set('active', 'dash'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Dashboard'); ?></h3>
		<span><?php echo __('Panoramica in tempo reale'); ?></span>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div id="dashStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<a href="<?php echo $this->Html->url(array('controller' => 'mails', 'action' => 'index')); ?>">
				<div class="panel-stat3 bg-success">
					<h2 class="m-top-none"><?=$allmail;?></h2>
					<h5><?php echo __('Email in archivio'); ?></h5>
					<div class="stat-icon"><i class="fa fa-envelope"></i></div>
				</div>
			</a>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$allsending;?></h2>
				<h5><?php echo __('Invii effettuati'); ?></h5>
				<div class="stat-icon"><i class="fa fa-plane"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<a href="<?php echo $this->Html->url(array('controller' => 'lists', 'action' => 'index')); ?>">
				<div class="panel-stat3 bg-success">
					<h2 class="m-top-none"><?=$mailinglistCount;?></h2>
					<h5><?php echo __('Liste in archivio'); ?></h5>
					<div class="stat-icon"><i class="fa fa-group"></i></div>
				</div>
			</a>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Info'); ?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div>
							<div class="text-center"><b><?=__('Email da inviare')?></b></div>
							<?php
								$statTotal = $statDone = 0;
								foreach($sendings as $sending) {
									$statTotal += $sending['Recipient']['total'];
									$statDone += $sending['Recipient']['done'];
								}
							?>
							<div id="total" data-total="<?=$statTotal?>" data-value="<?=$statTotal - $statDone?>" class="text-center">
						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Invii in corso'); ?>
				</div>
				<div class="panel-body nopadding">
					<table id="sendingTable" class="table table-striped table-bordered table-hover interactive table-centered">
						<thead <?=empty($sendings)?'style="display:none"':''?>>
							<tr>
								<th><?php echo __('Email'); ?></th>
								<th><?php echo __('Completamento'); ?></th>
							</tr>
						</thead>
						<?php if(empty($sendings)) : ?>
							<tbody>
								<tr class="empty-placeholder">
									<td><h4><?php echo __('Nessun invio in corso'); ?></h4></td>
								</tr>
							</tbody>
						<?php else: ?>
							<tbody>
							<?php foreach ($sendings as $sending): ?>
								<tr 
									data-url="<?=$this->Html->url(array('controller' => 'sendings', 'action' => 'view', $sending['Sending']['id']));?>"
									data-sending="<?php echo $sending['Sending']['id']; ?>"
									class="<?=$sending['Sending']['errors']?'error-message':'';?>"
								>
									<td>
										<?php if($sending['Sending']['errors']) : ?>
											<i class="icon icon-warning-sign"></i>
										<?php endif; ?>
										<?php echo h($sending['Mail']['name']); ?>
									</td>
									<td>
										<?php if(isset($sending['Recipient'])): ?>
											<span class="doneField"><?=$sending['Recipient']['done'];?></span>
											<span>/</span>
											<span class="totalField"><?=$sending['Recipient']['total'];?></span>
										<?php endif; ?>
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
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('In attesa'); ?>
				</div>
				<div class="panel-body nopadding">
					<table id="waitingTable" class="table table-striped table-bordered table-hover interactive table-centered">
						<thead <?=empty($waitings)?'style="display:none"':''?>>
							<tr>
								<th><?php echo __('Email'); ?></th>
								<th><?php echo __('Data creazione'); ?></th>
								<th><?php echo __('Data invio'); ?></th>
							</tr>
						</thead>
						<?php if(empty($waitings)) : ?>
							<tbody>
								<tr class="empty-placeholder">
									<td><h4 class="text-center"><?php echo __('Nessun invio in attesa'); ?></h4></td>
								</tr>
							</tbody>
						<?php else: ?>
							<tbody>
							<?php foreach ($waitings as $waiting): ?>
								<tr 
									data-url="<?=$this->Html->url(array('controller' => 'sendings', 'action' => 'view', $waiting['Sending']['id']));?>"
									data-sending="<?php echo $waiting['Sending']['id']; ?>"
								>
									<td><?php echo h($waiting['Mail']['name']); ?></td>
									<td>
										<?php
											$date = DateTime::createFromFormat('Y-m-d H:i:s', $waiting['Sending']['created']);
											echo $this->SafeDate->dateForUser($date);
											//echo $date->format('d/m/Y H:i:s'); 
										?>
									</td>
									<td>
										<?php
											if(empty($waiting['Sending']['time'])) {
												$date = DateTime::createFromFormat('Y-m-d H:i:s', $waiting['Sending']['created']);
											}
											else {
												$date = new DateTime();
												$date->setTimestamp($waiting['Sending']['time']);	
											}
											//echo $date->format('d/m/Y H:i:s');
											echo $this->SafeDate->dateForUser($date);
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
</div>


