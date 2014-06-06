<?php $this->set('title_for_layout', __('Nuova Chiave API')); ?>
<?php $this->Html->addCrumb('Gestione API', '/apis/index'); ?>
<?php $this->Html->addCrumb('Nuova Chiave', '/apis/add'); ?>

<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Nuova Chiave API'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<?php echo $this->Form->create('Api', array('method' => 'post', 'inputDefaults' => array('autocomplete' => 'off'))); ?>
	<div class="grey-container shortcut-wrapper">
		<?php
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-save"></i></span><span class="text">'.__('Salva').'</span>',
				'#',
				array(
					'class' => 'shortcut-link', 
					'escape' => false,
					'title' => __('Invia'),
					'onclick' => "$('#ApiAddForm').submit();"
				)
			);
		
			echo $this->Html->link(
				'<span class="shortcut-icon"><i class="fa fa-undo"></i></span><span class="text">'.__('Annulla').'</span>',
				array('controller' => 'apis', 'action' => 'index'), 
				array(
					'class' => 'shortcut-link',
					'escape' => false,
					'title' => __('Annulla'),
				)
			);
		?>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-8">
				<div class="form-group">
					<label class="control-label required" for="ApiName"><?php echo __('Nome'); ?></label>
						<?php 
							echo $this->Form->input(
								'name', 
								array(
									'label' => false,
									'class' => 'form-control'
								)
							); 
						?>
				</div>
				<div class="form-group">
					<label class="control-label" for="ApiDescription"><?php echo __('Descrizione'); ?></label>
					<?php 
						echo $this->Form->input(
							'description', 
							array(
								'label' => false,
								'class' => 'form-control',
								'type' => 'textarea'
							)
						); 
					?>
				</div>
			</div>
		</div>
		<div id="filterContainer">
			<div style="margin-bottom:20px;"><b><?php echo __('Gestione Permessi'); ?></b></div>
			<ul id="acl" class="tree list-unstyled">
				<li>
					<small><b><?php echo __('Gestione diretta membri'); ?></b></small>
					<ul>
						<li>
							<?php 
								echo $this->Form->input(
									'Api.acl.members.show', 
									array('label' => __('Visualizza Membri'), 'type' => 'checkbox')
								);
							?>
						</li>
						<li>
							<?php 
								echo $this->Form->input(
									'Api.acl.members.create_edit', 
									array('label' => __('Crea/Modifica Membri'), 'type' => 'checkbox')
								);
							?>
						</li>
						<li>
							<?php 
								echo $this->Form->input(
									'Api.acl.members.subscribe_unsubscribe', 
									array('label' => __('Iscrivi/Disiscrivi Membri'), 'type' => 'checkbox')
								);
							?>
						</li>
						<li>
							<?php 
								echo $this->Form->input(
									'Api.acl.members.delete', 
									array('label' => __('Elimina Membri'), 'type' => 'checkbox')
								);
							?>
						</li>
					</ul>
				</li>
				<li>
					<small><b><?php echo __('Gestione liste'); ?></b></small>
					<ul>
						<?php foreach($lists as $list => $listName) : ?>
						<li>
							<small><b><?php echo $listName; ?></b></small>
							<ul>
								<li>
									<?php 
										echo $this->Form->input(
											'Api.acl.lists.'.$list.'.show', 
											array('label' => __('Visualizza Membri'), 'type' => 'checkbox')
										);
									?>
								</li>
								<li>
									<?php 
										echo $this->Form->input(
											'Api.acl.lists.'.$list.'.edit', 
											array('label' => __('Modifica Informazioni Membri'), 'type' => 'checkbox')
										);
									?>
								</li>
								<li>
									<?php 
										echo $this->Form->input(
											'Api.acl.lists.'.$list.'.create_subscribe', 
											array('label' => __('Crea e Iscrivi Membri'), 'type' => 'checkbox')
										);
									?>
								</li>
								<li>
									<?php 
										echo $this->Form->input(
											'Api.acl.lists.'.$list.'.unsubscribe', 
											array('label' => __('Disiscrivi Membri'), 'type' => 'checkbox')
										);
									?>
								</li>
								<li>
									<?php 
										echo $this->Form->input(
											'Api.acl.lists.'.$list.'.delete', 
											array('label' => __('Elimina Membri'), 'type' => 'checkbox')
										);
									?>
								</li>
							</ul>
						</li>
						<?php endforeach; ?>
					</ul>
				</li>
			</ul>
		</div>
	</div>
<?php echo $this->Form->end(); ?>

