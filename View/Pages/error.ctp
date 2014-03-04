<?php $this->layout = 'one_column'; ?>
<div class="container">
	<div style="margin-top:50px;" class="padding-md">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 text-center">
				<div class="h5">Errore Imprevisto!</div>
				<h1 class="m-top-none error-heading">500</h1>
			
				<p class="font-14"><?php echo __('Il Server ha riscontrato un problema e non è in grado di completare la richiesta'); ?></p>
				<h4 class="text-danger">
					<i class="fa fa-wrench"></i>
					<?php echo __('Stiamo lavorando per risolverlo'); ?>
				</h4>
			
				<div class="m-bottom-md">Esegui una di queste azioni</div>
				<?php 
					echo $this->Html->link(
						__('<i class="fa fa-home"></i> Torna alla Dashboard'), 
						'/', 
						array('escape' => false, 'class' => 'btn btn-success m-bottom-sm')
					); 
				?>
			</div>
		</div>
	</div>
</div>
