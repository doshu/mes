<?php $this->set('title_for_layout', __('Disiscrizione di').' '.h($unsubscription['Member']['email'])); ?>
<?php $this->set('active', 'member'); ?>
<?php
	App::uses('Mailinglist', 'Model');
	$Mailinglist = new Mailinglist();
	
	if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) {
		if(
			$this->request->params['named']['from'] == 'mailinglists' &&
			isset($this->request->params['named']['mailinglist']) && 
			!empty($this->request->params['named']['mailinglist'])
		) {
			$Mailinglist->recursive = -1;
			$mailinglist = $Mailinglist->read(null, $this->request->params['named']['mailinglist']);
				
		}
	}

?>

<?php if(isset($this->request->params['named']['from']) && !empty($this->request->params['named']['from'])) : ?>
	<?php
		switch($this->request->params['named']['from']) {
			case 'sendings':
				$this->Html->addCrumb('Email', '/mails/index');
				$this->Html->addCrumb(h($unsubscription['Sending']['Mail']['name']), '/mails/view/'.$unsubscription['Sending']['Mail']['name']);
				$this->Html->addCrumb('Invio #'.$unsubscription['Sending']['id'], '/sendings/view/'.$unsubscription['Sending']['id']);
				$this->Html->addCrumb('Disiscrizioni', '/sendings/unsubscribed/'.$unsubscription['Sending']['id']);
				$this->Html->addCrumb(h($unsubscription['Member']['email']), '/unsubscriptions/view/'.$unsubscription['Unsubscription']['id']);
			break;
			case 'mailinglists' && isset($mailinglist) :
				$this->Html->addCrumb('Liste', '/mailinglists/index');
				$this->Html->addCrumb(h($mailinglist['Mailinglist']['name']), '/mailinglists/view/'.$mailinglist['Mailinglist']['id']);
				$this->Html->addCrumb('Disiscrizioni', '/mailinglists/unsubscribed/'.$mailinglist['Mailinglist']['id']);
				$this->Html->addCrumb(h($unsubscription['Member']['email']), '/unsubscriptions/view/'.$unsubscription['Unsubscription']['id']);
			break;
			case 'members':
				$this->Html->addCrumb('Membri', '/members/index');
				$this->Html->addCrumb(h($unsubscription['Member']['email']), '/members/view/'.$unsubscription['Member']['id']);
				$this->Html->addCrumb('Disiscrizioni', '/mailinglists/unsubscribed/'.$unsubscription['Member']['id']);
				$this->Html->addCrumb(h($unsubscription['Member']['email']), '/unsubscriptions/view/'.$unsubscription['Unsubscription']['id']);
			break;
			default:
				$this->Html->addCrumb('Disiscrizioni', '/unsubscriptions/index');
				$this->Html->addCrumb(h($unsubscription['Member']['email']), '/unsubscriptions/view/'.$unsubscription['Unsubscription']['id']);
		}
	?>
<?php else: ?>
	<?php $this->Html->addCrumb('Disiscrizioni', '/unsubscriptions/index'); ?>
	<?php $this->Html->addCrumb(h($unsubscription['Member']['email']), '/unsubscriptions/view/'.$unsubscription['Unsubscription']['id']); ?>
<?php endif; ?>
<div class="main-header clearfix">
	<div class="headline">
		<h3 class="no-margin"><?php echo __('Disiscrizione di').' '.h($unsubscription['Member']['email']); ?></h3>
		<span class="line"></span>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo __('Dettagli Disiscrizione'); ?>
				</div>
				<div class="panel-body nopadding">
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Email'); ?></span>
								</td>
								<td>
									<?php echo h($unsubscription['Member']['email']); ?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Data disiscrizione'); ?></span>
								</td>
								<td>
									<?php 
										$date = DateTime::createFromFormat('Y-m-d H:i:s', $unsubscription['Unsubscription']['created']); 
										echo $this->SafeDate->dateForUser($date);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Disiscritto da'); ?></span>
								</td>
								<td>
									<?php 
										$linklist = array();
										foreach($unsubscription['Mailinglist'] as $mailinglist) {
											$linklist[] = $this->Html->link($mailinglist['name'],
												array('controller' => 'mailinglists', 'action' => 'view', $mailinglist['id'])
											);
										}
										echo implode(', ', $linklist);
									?>
								</td>
							</tr>
							<tr>
								<td>
									<span class="custom-label"><?php echo __('Tramite'); ?></span>
								</td>
								<td>
									<?php 
										echo $this->Html->link(
											__('Invio #').$unsubscription['Sending']['id'], '/sendings/view/'.$unsubscription['Sending']['id'],
											array('controller' => 'sendings', 'action' => 'view', $unsubscription['Sending']['id'])
										);
											
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
