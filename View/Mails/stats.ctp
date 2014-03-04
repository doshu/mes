<?php //$this->Html->script('excanvas.compiled.js', array('block' => 'scriptLteIE8')); ?>
<?php //$this->Html->script('Chart.min.js', array('inline' => false)); ?>
<?php $this->Html->script('raphael-min', array('inline' => false)); ?>
<?php $this->Html->script('g.raphael-min', array('inline' => false)); ?>
<?php $this->Html->script('g.pie-min', array('inline' => false)); ?>
<?php $this->Html->css('jquery-jvectormap.css', array('inline' => false)); ?>
<?php $this->Html->script('jquery-jvectormap.min.js', array('inline' => false)); ?>
<?php $this->Html->script('jquery-jvectormap-world-mill-en.js', array('inline' => false)); ?>

<?php $this->set('title_for_layout', __('Statistiche')); ?>
<?php $this->set('active', 'stats'); ?>
<?php $this->Html->addCrumb('Email', '/mails/index'); ?>
<?php $this->Html->addCrumb('Statistiche', '/mails/stats'); ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Statistiche'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div id="mailStat" class="site-stats clearfix">
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$usage['sended'];?></h2>
				<h5><?php echo __('Email Inviate'); ?></h5>
				<div class="stat-icon"><i class="fa fa-plane"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$usage['opened'];?></h2>
				<h5><?php echo __('Email lette'); ?></h5>
				<div class="stat-icon"><i class="fa fa-folder-open"></i></div>
			</div>
		</div>
		<div class="col-lg-3">
			<div class="panel-stat3 bg-success">
				<h2 class="m-top-none"><?=$usage['followed'];?></h2>
				<h5><?php echo __('Email con link seguiti'); ?></h5>
				<div class="stat-icon"><i class="fa fa-external-link-square"></i></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Device più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="deviceStats"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Browser più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="browserStats"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Sistemi operativi più utilizzati'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="osStats"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Mappa email lette'); ?>
				</div>
				<div class="panel-body text-center">
					<div id="geoChart" style="height:300px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	<?php 
		echo $this->Javascript->setGlobal(array(
			'browserStatsDataset' => Hash::extract($browsers, '{n}.Recipient'),
			'deviceStatsDataset' => Hash::extract($devices, '{n}.Recipient'),
			'osStatsDataset' => Hash::extract($oss, '{n}.Recipient'),
			'usageDataset' => $usage,
			'geoDataset' => $geo
		));
	?>
</script>

