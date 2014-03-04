<?php
	
	$this->layout = "one_column";
?>
<div class="container">
	<?php
		echo $this->Html->link(
			__('Torna alla Homapage'), 
			'/', 
			array('class' => 'btn btn-primary pull-right', 'style' => 'margin-top:20px;')
		);
	?>
	<div class="main-header clearfix">
		<div class="headline">
			<h3 class="no-margin"><?php echo __('Cookie disabilitati!'); ?></h3>
			<span class="line"></span>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<p><?= __('Se stai tentando di utilizzare il tuo account e viene visualizzato questo errore, probabilmente i cookie del tuo browser sono disattivati.');?></p>
			<p><?= __('Devi attivare i cookie nel browser per utilizzare il tuo account e tutte le funzioni che richiedono il login');?></p>
		
			<div style="margin-top:30px;">
				<h4><?= __('Che cosa sono i cookie?'); ?></h4>

				<p><?= __('Un cookie è un piccolo file inviato al tuo computer quando visiti un sito web. I cookie consentono ai siti di memorizzare informazioni sulla tua visita, come la tua lingua preferita e altre impostazioni. Con tali informazioni, i siti possono facilitare la tua prossima visita e rendere il sito più utile per le tue esigenze.');?></p>
			</div>
		
			<div style="margin-top:30px;">
				<h4><?= __('Come abilito i cookie?'); ?></h4>

				<div class="tabbable tabs-left" style="margin-bottom:40px;">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#ie" data-toggle="tab">Internet Explorer</a></li>
						<li><a href="#firefox" data-toggle="tab">Mozzilla Firefox</a></li>
						<li><a href="#chrome" data-toggle="tab">Google Chrome</a></li>
						<li><a href="#opera" data-toggle="tab">Opera</a></li>
						<li><a href="#safari" data-toggle="tab">Safari</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="ie">
							<p>Selezionare il menu <b>Strumenti</b>, scegliere <b>Opzioni Internet</b> e cliccare sul tab <b>Privacy</b></p>
							<p>Cliccare il pulsante il <b>Avanzate</b> e spuntare la casella <b>Accetta sempre i cookie della sessione</b>.</p>
						</div>
						<div class="tab-pane" id="firefox">
							<p>Selezionare il menu <b>Strumenti</b>, scegliere <b>Opzioni</b> e cliccare sul tab <b>Privacy</b></p>
							<p>In <b>Impostazioni cronologia</b> scegliere <b>Utilizza impostazioni personalizzate</b>.</p> 
							<p>Spuntare le caselle <b>Accetta i cookie dai siti</b> e <b>Accetta i cookie di terze parti</b>.</p>
						</div>
						<div class="tab-pane" id="chrome">
							<p>Cliccare il pulsante a forma di chiave inglese, scegliere <b>Opzioni</b> e cliccare sul tab <b>Roba da smanettoni</b></p>
							<p>Cliccare su <b>Impostazioni contenuti</b> e spuntare la casella <b>Consenti il salvataggio dei dati in locale (consigliata)</b>.</p>
						</div>
						<div class="tab-pane" id="opera">
							<p>Selezionare il menu <b>Menu</b>, scegliere <b>Impostazioni/Preferenze</b></p>
							<p>Cliccare sul tab <b>Avanzate</b> e, nella sezione <b>Cookie</b>, spuntare la casella <b>Accetta i cookie</b>.</p>
						</div>
						<div class="tab-pane" id="safari">
							<p>Cliccare il pulsante a forma di ingranaggio e scegliere <b>Preferenze</b></p>
							<p>Cliccare sul tab <b>Privacy</b> e spuntare la casella <b>Mai</b></p>.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
