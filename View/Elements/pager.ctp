<?php 
	$pagerAction = array(
		'plugin' => $this->request->params['plugin'],
		'controller' => $this->request->params['controller'],
		'action' => $this->request->params['action'],
	);
	$pagerAction += $this->request->params['pass'];
	$pagerAction += $this->request->params['named'];
	unset($pagerAction['page']);
?>
<span><?=__('Elementi per pagina');?> </span>
<?php
	$available = array('10' , '20', '50', '100', '200');
?>
<select onchange="window.location.href = $(this).find('option:selected').attr('data-url');">
	<?php foreach($available as $n) : ?>
	<?php $pagerAction['limit'] = $n ?>
	<option 
		value="<?=$n?>" 
		data-url="<?=Router::url($pagerAction);?>" 
		<?=$this->Paginator->param('limit') == $n?'selected="selected"':'';?>
	>
		<?=$n?>
	</option>
	<?php endforeach; ?>
</select>
