<span><?=__('Elementi per pagina');?> </span>
<?php
	$available = array('10' , '20', '50', '100', '200');
?>
<select onchange="window.location.href = $(this).find('option:selected').attr('data-url');">
	<?php foreach($available as $n) : ?>
	<option 
		value="<?=$n?>" 
		data-url="<?=$this->Paginator->url(array('limit' => $n))?>" 
		<?=$this->Paginator->param('limit') == $n?'selected="selected"':'';?>
	>
		<?=$n?>
	</option>
	<?php endforeach; ?>
</select>
