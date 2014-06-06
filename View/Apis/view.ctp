<?php $this->set('title_for_layout', h($key['Api']['name'])); ?>
<?php $this->Html->addCrumb('Gestione API', '/apis/index'); ?>
<?php $this->Html->addCrumb(h($key['Api']['name']), '/apis/view/'.$key['Api']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo h($key['Api']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper enable-affix">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Chiave API').'</span>',
			array('action' => 'edit', $key['Api']['id']),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Chiave'),
			)
		);
	
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Chiave API').'</span>',
			array('action' => 'delete', $key['Api']['id']),
			array(
				'data-to-confirm' => __("Sei sicuro di voler eliminare la chiave?"), 
				'escape' => false,
				'title' => __('Elimina Chiave', true),
				'class' => 'shortcut-link', 
			)
		);
		
	?>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Chiave API'); ?>
				</div>
				<div class="panel-body nopadding table-overflow">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome'); ?></span>
								</td>
								<td>
									<?php echo h($key['Api']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Descrizione'); ?></span>
								</td>
								<td>
									<?php echo h($key['Api']['description']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Salt'); ?></span>
								</td>
								<td>
									<?php echo h($key['Api']['salt']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Password'); ?></span>
								</td>
								<td>
									<?php echo h($key['Api']['enckey']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Chiave Client'); ?></span>
								</td>
								<td>
									<?php echo h($key['Api']['clientkey']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $key['Api']['created']); 
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
