<?php
	
	$this->layout = "one_column";
?>
<div class="container-fluid">
	<h1 class="page-header"><?php echo __('Pagina non trovata!'); ?></h1>
	<div style="font-size:23px; line-height:25px;">
		<div><?= __('Il link seguito, potrebbe essere vecchio e non esistere più.');?></div>
		<div><?= __('Ti suggeriamo di contattare il mittente della Email ricevuta per comunicare l\'errore.');?></div>
		<div style="width:300px; margin:auto; margin-top:30px;">
			<?php 
				echo $this->Html->image('mylogo.png', array('style' => 'width:300px'));
			?>
		</div>
	</div>
</div>
