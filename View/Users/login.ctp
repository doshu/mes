<?php
	$this->layout = 'one_column';
?>
<?php $this->set('title_for_layout', __('Effettua il login')); ?>

<div class="login-wrapper">
	<div class="text-center">
		<h2 class="" style="font-weight:bold">
			<?php echo $this->Html->image('likeasirblack.png', array('width' => 75)); ?>
			<span style="color:#ccc; text-shadow:0 1px #fff">MES</span>
		</h2>
	</div>
	<div class="login-widget">
		<div class="panel panel-default">
			<div class="panel-heading">
				<?php echo __('Login'); ?>
			</div>
			<div class="panel body">
				<?php echo $this->Form->create('User', array('class' => '')); ?>
					 <div class="form-group">
						<label class="control-label" for="UserUsername"><?=__('Username');?></label>
						<div class="controls">
							<?php
								echo $this->Form->input('username', array('label' => false, 'div' => false, 'class' => 'form-control'));
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label" for="UserPassword"><?=__('Password');?></label>
						<div class="controls">
							<?php
								echo $this->Form->input('password', array('label' => false, 'div' => false ,'class' => 'form-control'));
							?>
						</div>
					</div>
					<div class="form-group">
						<div class="controls">
							<?php
								echo $this->Form->input(
									__('Accedi'), 
									array(
										'type' => 'submit', 
										'label' => false, 
										'div' => false,
										'class' => 'btn btn-success'
									)
								);
							?>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>


