<!DOCTYPE html>
<html lang="it">
	<head>
		<?php echo $this->Html->charset(); ?>
		<title><?php echo 'Powamail - '.$title_for_layout; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php
			echo $this->Html->meta('icon');
			echo $this->Html->css(array(
				'bootstrap.min',
				'select2',
				'doshu',
				'doshu-skin',
				'font-awesome.min',
				'jquery.datetimepicker',
				'layout'
			));
		?>

		<?php
		
			echo $this->Html->script(array(
				'jquery-1.9.1.min',
				'modernizr',
				'bootstrap.min',
				'select2.min',
				'doshupie',
				'layout',
				'ckeditor/ckeditor.js',
				'doshuupload',
				'jquery.datetimepicker',
				'datetime_locales/i18n'
			));
			
			echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>
		<?php echo $this->Html->script('respond'); ?>
		<?php	
			echo $this->element('ready');
		?>
		<?php 
			$baseurl = dirname(dirname($_SERVER['PHP_SELF']));
			if($baseurl[strlen($baseurl)-1] != '/')
				$baseurl .= '/';
		?>
		<!--[if lte IE 8]>
			<?php echo $this->fetch('scriptLteIE8'); ?>
		<![endif]-->
		<script>var BASEURL = '<?php echo $baseurl;?>'; </script>
	</head>
	<body>
		<div id="wrapper" class="">
			<div id="top-nav" class="fixed skin-6">
				<a href="#" class="brand">
					<button type="button" class="navbar-toggle" style="float:none;" id="showMenuButton">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<strong>POWAMAIL</strong>
					<span style="font-size:10px; line-height:10px;">Mail like a Pro!</span>
				</a>
      
				<ul class="nav-notification clearfix">
					<li class="profile dropdown">
						<?php
							echo $this->Html->link(
								'<i class="fa fa-cogs icon-white"></i>',
								'#',
								array(
									'escape' => false, 
									'class' => 'dropdown-toggle',
									'data-toggle' => 'dropdown',
									'data-placement' => 'bottom',
									'title' => __('Impostazioni')
								)
							);
						?>
						<ul class="dropdown-menu">
							<li>
								<?php 
									echo $this->Html->link(
										__('Impostazioni Account'), 
										array('plugin' => false, 'controller' => 'users', 'action' => 'settings'),
										array('class' => 'main-link')
									);
								?>
							</li>
							<li>
								<?php
									echo $this->Html->link(
										__('Gestisci Campi Membri'),
										array('plugin' => false, 'controller' => 'memberfields', 'action' => 'index'),
										array(
											'class' => 'main-link', 
										)
									);
								?>
							</li>
							<li>
								<?php 
									echo $this->Html->link(
										__('Logout'), 
										array('plugin' => false, 'controller' => 'users', 'action' => 'logout'),
										array('class' => 'main-link')
									);
								?>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<aside class="skin-6 fixed">
				<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
					<div class="sidebar-inner scrollable-sidebar" style="overflow: hidden; width: auto; height: 100%;">
						<div class="size-toggle">
							<a href="<?=$this->Html->url(array('controller' => 'users', 'action' => 'logout'));?>">
								<i class="icon icon-power-off" style="color:#fff;"></i>
							</a>
						</div>
						<div class="user-block clearfix">
							<?php echo $this->Html->image('mylogo.png'); ?>
							<div class="detail">
								<strong>POWAMAIL</strong>
								<div>Mail like Pro!</div>
							</div>
						</div>
						<div class="main-menu">
							<ul>
								<li class="<?=(isset($active) && $active == 'dash')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'mails', 'action' => 'dashboard'));?>">
										<i class="fa fa-home"></i>
										<span class="text"><?php echo __('Dashboard'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'email')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'mails', 'action' => 'index'));?>">
										<i class="fa fa-envelope"></i>
										<span class="text"><?php echo __('Email'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'template')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'templates', 'action' => 'index'));?>">
										<i class="fa fa-clipboard"></i>
										<span class="text"><?php echo __('Template'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'list')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'mailinglists', 'action' => 'index'));?>">
										<i class="fa fa-group"></i>
										<span class="text"><?php echo __('Liste'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'member')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'members', 'action' => 'index'));?>">
										<i class="fa fa-user"></i>
										<span class="text"><?php echo __('Membri'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'smtp')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'smtps', 'action' => 'index'));?>">
										<i class="fa fa-exchange"></i>
										<span class="text"><?php echo __('Indiizzi Invio'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
								<li class="<?=(isset($active) && $active == 'stats')?'active':'';?>">
									<a href="<?=$this->Html->url(array('controller' => 'mails', 'action' => 'stats'));?>">
										<i class="fa fa-book"></i>
										<span class="text"><?php echo __('Statistiche'); ?></span>
										<span class="menu-hover"></span>
									</a>
								<li>
							</ul>
						</div>
					</div>
				</div>
			</aside>
			<div id="main-container">
				<noscript class="noscript-noscript">
					<div class="alert alert-error noscript-alert">
						<?php echo __('Da qui in poi devi avere Javascript attivato per continuare'); ?>
					</div>
				</noscript>
				<?php echo $this->element('flash'); ?>
				<div id="breadcrumb">
					<?php echo $this->Html->getCrumbList(array('class' => 'breadcrumb'), 'Home'); ?>
				</div>
				<?php echo $this->fetch('content'); ?>
				<?php echo $this->element('sql_dump'); ?>
			</div>
			<footer id="footer">
				<?php echo __('MES è stato creato da Thomas Schiavello'); ?>
			</footer>
		</div>
	</body>
</html>
