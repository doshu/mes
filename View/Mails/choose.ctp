<?php $this->set('title_for_layout', __('Scegli Template')); ?>
<?php $this->set('active', 'email'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb('Scegli Template', '/mails/choose'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Scegli Template'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="clearfix" style="margin-bottom:10px;">
				<?php
					echo $this->Html->link(
						__('Continua Senza Template'),
						array('action' => 'add'),
						array(
							'class' => 'btn btn-primary pull-right '
						)
					);
				?>
			</div>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#personal_template" data-toggle="tab">
						<?php echo __('Template Personali'); ?>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active table-overflow" id="personal_template">
					<table class="table table-striped table-bordered table-hover table-centered">
						<thead>
							<th><?=__('Nome'); ?></th>
							<th><?=__('Descrizione'); ?></th>
							<th><?=__('Azioni'); ?></th>
							<th></th>
						</thead>
						<tbody>
						<?php foreach($personal_templates as $pt) : ?>
							<tr>
								<td><?=$pt['Template']['name']; ?></td>
								<td><?=$pt['Template']['description']; ?></td>
								<td>
									<?php
										echo $this->Html->link(
											'<i class="fa fa-picture-o" title="'.__('Apri').'"></i>',
											array('controller' => 'templates', 'action' => 'view', $pt['Template']['id']),
											array(
												'escape' => false,
												'target' => '_blank'
											)
										);
									?>
								</td>
								<td>
									<?php
										echo $this->Html->link(
											__('Usa'),
											array('action' => 'add', 'templatetype' => 'personal', 'template' => $pt['Template']['id']),
											array(
												'class' => 'btn btn-xs btn-primary'
											)
										);
									?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


