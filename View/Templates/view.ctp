<?php $this->set('title_for_layout', h($template['Template']['name'])); ?>
<?php $this->set('active', 'template'); ?>
<?php $this->Html->addCrumb('Template', '/templates/index'); ?>
<?php $this->Html->addCrumb(h($template['Template']['name']), '/templates/view/'.$template['Template']['id']); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3><?php echo h($template['Template']['name']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="grey-container shortcut-wrapper">
	<?php
	
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-envelope"></i></span><span class="text">'.__('Crea Email').'</span>',
			array('controller' => 'mails', 'action' => 'add', 'templatetype' => 'personal', 'template' => $template['Template']['id']),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Crea Email')
			)
		);
		
		echo $this->Html->link(
			'<span class="shortcut-icon"><i class="fa fa-pencil"></i></span><span class="text">'.__('Modifica Template').'</span>',
			array('action' => 'edit', $template['Template']['id']),
			array(
				'class' => 'shortcut-link',
				'escape' => false,
				'title' => __('Modifica Template')
			)
		);
		
		echo $this->Form->postLink(
			'<span class="shortcut-icon"><i class="fa fa-times"></i></span><span class="text">'.__('Elimina Template').'</span>',
			array('action' => 'delete', $template['Template']['id']),
			array(
				'class' => 'shortcut-link',
				'data-to-confirm' => __("Sei sicuro di voler eliminare il Template?"),
				'escape' => false,
				'title' => __('Elimina Template')
			)
		);
	?>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Template'); ?>
				</div>
				<div class="panel-body">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Nome'); ?></span>
								</td>
								<td>
									<?php echo h($template['Template']['name']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Descrizione'); ?></span>
								</td>
								<td>
									<?php echo h($template['Template']['description']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data creazione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $template['Template']['created']); 
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
											array('action' => 'preview', $template['Template']['id']),
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
									<?php echo h($template['Template']['text']); ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<?php //debug($mail); ?>
</div>
