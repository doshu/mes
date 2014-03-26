<?php $this->set('title_for_layout', __('Membri')); ?>
<?php $this->set('active', 'member'); ?>
<?php App::uses('Memberfield', 'Model'); ?>
<?php App::uses('Mailinglist', 'Model'); ?>
<?php $this->Html->script('members/validate', array('inline' => false)); ?>
<?php $this->Javascript->setGlobal(array('token' => $this->request->params['_Token']['key'])); ?>
<?php 
	$this->Javascript->setGlobal(array(
		'addressIsValid' => Member::isValid,
		'addressIsNotValid' => Member::isNotValid,
		'addressCannotValidated' => Member::cannotValidate
	)); 
?>
<?php
	if($from == 'members') { 
		$this->Html->addCrumb('Membri', '/members/index');
	}
	elseif($from == 'mailinglists' && isset($scope)) {
		$this->set('active', 'list');
		$Mailinglist = new Mailinglist();
		$Mailinglist->recursive = -1;
		$name = $Mailinglist->read('name', $scope);
		$this->Html->addCrumb('Liste', '/mailinglists/index');
		$this->Html->addCrumb($name['Mailinglist']['name'], '/mailinglists/view/'.$scope);
		$this->Html->addCrumb('Membri della Lista', '/members/mailinglist/'.$scope);
	}
?>
<div class="main-header clearfix">
	<div class="page-title">
		<h3 class="headline"><?php echo __('Validazione Indirizzi'); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Indirizzi'); ?>
				</div>
				<div class="panel-body nopadding">
					<ul id="toValidateList" class="list-group">
						<?php if(!empty($members)) : ?>
							<?php foreach ($members as $member): ?>
								<li class="list-group-item" data-url="<?=$this->Html->url(array('controller' => 'members', 'action' => 'view', $member['Member']['id']));?>" data-member="<?php echo $member['Member']['id'];?>">
									<span class="result pull-right">
										<?php
											echo $this->Html->image('spinner_blu.gif');
										?>
									</span>
									<?php echo $member['Member']['email']; ?>
								</li>
							<?php endforeach; ?>
						<?php else: ?>
							<li><h4 style="text-align:center;"><?php echo __('Nessuna Membro trovato'); ?></h4></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>


