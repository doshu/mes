<?php $this->set('title_for_layout', h($mailinglist['Mailinglist']['name'])); ?>
<?php $this->set('active', 'list'); ?>
<?php $this->Html->addCrumb('Liste', '/mailinglists/index'); ?>
<?php $this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglists/view/'.$mailinglist['Mailinglist']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo h($mailinglist['Mailinglist']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Lista').'</span>',
			array('action' => 'edit', $mailinglist['Mailinglist']['id']),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Lista'),
			)
		);
	
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-user"></i></span><span class="text">'.__('Vedi Membri').'</span>',
			array('controller' => 'members', 'action' => 'mailinglist', $mailinglist['Mailinglist']['id']),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Vedi Membri'),
			)
		);
		
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-download"></i></span><span class="text">'.__('Esporta Membri').'</span>',
			array('controller' => 'mailinglists', 'action' => 'export', $mailinglist['Mailinglist']['id']),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Esporta Membri')
			)
		);
	?>
</div>
<div class="container-fluid">
	<div id="mailinglistStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<a href="<?=$this->Html->url(array('action' => 'unsubscribed', $mailinglist['Mailinglist']['id']));?>">
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
					<?php echo __('Dettagli Lista'); ?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome'); ?></span>
								</td>
								<td>
									<?php echo h($mailinglist['Mailinglist']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Descrizione'); ?></span>
								</td>
								<td>
									<?php echo h($mailinglist['Mailinglist']['description']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $mailinglist['Mailinglist']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Numero Membri'); ?></span>
								</td>
								<td>
									<?php echo $mailinglist['Mailinglist']['members_count']; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
