<!DOCTYPE html>
<html lang="it">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?>
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
			echo $this->Html->meta('icon');

			echo $this->Html->css(array(
				'bootstrap.min',
				'select2',
				'doshu',
				'doshu-skin',
				'font-awesome.min',
				'layout'
			));
		?>
		<!--[if IE 7]>
			<?php echo $this->Html->css('font-awesome-ie7.min.css'); ?>
		<![endif]-->
		<?php
			echo $this->Html->script(array(
				'jquery-1.9.1.min',
				'modernizr',
				'bootstrap.min',
				'select2.min',
				'doshupie',
				'layout',
			));

			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		
			echo $this->element('ready');
		?>
		<?php 
			$baseurl = dirname(dirname($_SERVER['PHP_SELF']));
			if($baseurl[strlen($baseurl)-1] != '/')
				$baseurl .= '/';
		?>
		<script>var BASEURL = '<?php echo $baseurl;?>'; </script>
	</head>
	<body>
		<?php echo $this->element('flash'); ?>
		<?php echo $this->fetch('content'); ?>
	</body>
</html>
