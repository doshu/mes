<?php $this->set('title_for_layout', h($smtp['Smtp']['email'])); ?>
<?php $this->set('active', 'smtp'); ?>
<?php $this->Html->addCrumb('Indirizzi Invio', '/smtps/index'); ?>
<?php $this->Html->addCrumb(h($smtp['Smtp']['email']), '/smtps/view/'.$smtp['Smtp']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo h($smtp['Smtp']['email']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Indirizzo').'</span>',
			array('action' => 'edit', $smtp['Smtp']['id']),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Indirizzo'),
			)
		);
	
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Indirizzo').'</span>',
			array('action' => 'delete', $smtp['Smtp']['id']),
			array(
				'data-to-confirm' => __("Sei sicuro di voler eliminare l'indirizzo?"), 
				'escape' => false,
				'title' => __('Elimina Indirizzo', true),
				'class' => 'shortcut-link', 
			)
		);
	?>
</div>
<div class="container-fluid">
	<ul id="smtpStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$count;?></h2>
				<h5><?php echo __('Invii da questo indirizzo'); ?></h5>
				<div class="stat-icon"><i class="fa fa-plane"></i></div>
			</div>
		</div>
	</ul>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Indirizzo di Invio'); ?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Email'); ?></span>
								</td>
								<td>
									<?php echo h($smtp['Smtp']['email']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome'); ?></span>
								</td>
								<td>
									<?php echo h($smtp['Smtp']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Host'); ?></span>
								</td>
								<td>
									<?php echo h($smtp['Smtp']['host']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Porta'); ?></span>
								</td>
								<td>
									<?php echo h($smtp['Smtp']['port']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Username'); ?></span>
								</td>
								<td>
									<?php echo h($smtp['Smtp']['username']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Password'); ?></span>
								</td>
								<td>
									<?php echo str_repeat('*', mb_strlen($smtp['Smtp']['password'])); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Metodo Autenticazione'); ?></span>
								</td>
								<td>
									<?php
								
										echo h($smtp['Smtp']['authtype']);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Cifratura'); ?></span>
								</td>
								<td>
									<?php
										$enctype = array('none' => __('Nessuna'), 'tls' => __('TLS'), 'ssl' => __('SSL'));
										echo isset($enctype[$smtp['Smtp']['enctype']])?$enctype[$smtp['Smtp']['enctype']]:__('Nessuna');
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $smtp['Smtp']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
