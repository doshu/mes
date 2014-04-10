<?php $this->set('title_for_layout', h($memberfield['Memberfield']['name'])); ?>
<?php $this->set('active', 'member'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo h($memberfield['Memberfield']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Campo Membro').'</span>',
			array('action' => 'edit', $memberfield['Memberfield']['id']),
			array(
				'class' => 'shortcut-link', 
				'escape' => false,
				'title' => __('Modifica Campo Membro'),
			)
		);
		
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Campo Membro').'</span>',
			array('action' => 'delete', $memberfield['Memberfield']['id']),
			array(
				'data-to-confirm' => __('Sei sicuro di voler eliminare il campo membro?'), 
				'escape' => false,
				'title' => __('Elimina Campo Membro'),
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
					<?php echo __('Dettagli Campo Membro'); ?>
				</div>
				<div class="panel-body nopadding table-overflow">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Name'); ?></span>
								</td>
								<td>
									<?php echo h($memberfield['Memberfield']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Codice'); ?></span>
								</td>
								<td>
									<?php echo h($memberfield['Memberfield']['code']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Tipo'); ?></span>
								</td>
								<td>
									<?php
										$typeLabel = '';
										switch($memberfield['Memberfield']['type']) {
											case 0:
												$typeLabel = 'Campo testo';
											break;
											case 1:
												$typeLabel = 'Area di testo';
											break;
											case 2:
												$typeLabel = 'Sì/No';
											break;
											case 3:
												$typeLabel = 'Data';
											break;
										}
										echo $typeLabel;
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Mostra nella griglia'); ?></span>
								</td>
								<td>
									<?php echo $memberfield['Memberfield']['in_grid']?__('Sì'):__('No') ?>
								</td>
							</tr>
					
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
