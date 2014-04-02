<?php $this->layout = 'one_column'; ?>
<div class="container">
	<div style="margin-top:50px;" class="padding-md">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
				<div class="h5"><?php echo __('Errore Imprevisto!'); ?></div>
				<h1 class="m-top-none error-heading">500</h1>
			
				<p class="font-14"><?php echo __('Il Server ha riscontrato un problema e non è in grado di completare la richiesta'); ?></p>
				<h4 class="text-danger">
					<i class="fa fa-wrench"></i>
					<?php echo __('Stiamo lavorando per risolverlo'); ?>
				</h4>
			
				<div class="m-bottom-md"><?php echo __('Esegui una di queste azioni'); ?></div>
				<?php 
					echo $this->Html->link(
						'<i class="fa fa-home"></i> '.__('Torna alla Dashboard'), 
						'/', 
						array('escape' => false, 'class' => 'btn btn-success m-bottom-sm')
					); 
				?>
			</div>
		</div>
	</div>
</div>
