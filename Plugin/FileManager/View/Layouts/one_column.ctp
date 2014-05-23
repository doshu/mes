<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		
		echo $this->Html->meta('icon');

		echo $this->Html->css(array(
			'bootstrap.min',
			'doshu',
			'doshu-skin',
			'font-awesome.min',
			'layout',
			'FileManager.filemanager'
		));
	?>

	<?php
		echo $this->Html->script(array(
			'jquery-1.9.1.min',
			'modernizr',
			'bootstrap.min',
			'doshupie',
			'FileManager.layout',
			'doshuupload'
		));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
		
		echo $this->element('ready');
	?>
	<?php echo $this->Html->script('respond'); ?>
	<script>var BASEURL = '<?php echo dirname(dirname($_SERVER['PHP_SELF'])).'/';?>'; </script>
</head>
<body>
	<div id="header">
		<?php
			echo $this->Html->image('mylogo.png', array('style' => 'width:90px', 'id' => 'top-logo'));
		?>
		<h1 style="display:inline-block; margin-left:0;">POWAMAIL</h1>
		<span style="font-size:10px; line-height:10px;">Mail like a Pro!</span>
	</div>
	<div id="content">
		<?php echo $this->element('flash'); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
		
		<?php echo $this->element('sql_dump'); ?>
</body>
</html>
