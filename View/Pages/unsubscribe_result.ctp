<?php
	
	$this->layout = "one_column";
	if(isset($this->request->query['status']) && $this->request->query['status'] == 1) {
		$title = __('Disiscrizione avvenuta con successo');
		$text = '';
	}
	else {
		$title = __('Errore durante la disiscrizione');
		$text = __('Ti suggeriamo di contattare il mittente della Email ricevuta per comunicare l\'errore.');
	}
?>
<div class="container-fluid">
	<h1 class="page-header"><?php echo $title; ?></h1>
	<div style="font-size:23px; line-height:25px;">
		<div><?= $text;?></div>
		<div style="width:400px; margin:auto;">
			<?php 
				//echo $this->Html->image('me_gusta.gif', array('style' => 'width:400px'));
			?>
		</div>
	</div>
</div>
