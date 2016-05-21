
<br>
<h4><i class="fa fa-upload"></i> Arquivo do edital</h4>

<br>
<h5>Selecione o PDF contendo o edital do processo seletivo. </h5>
<br>
	
	<div class="row">
		<div class="col-md-4">
			<?= form_label("PDF do edital <small><i>(Arquivo '.pdf' apenas)</i></small>:", "notice_file"); ?>
		</div>

		<div class="col-md-3">
			<?= form_input($noticeFile); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
			<?= form_button($submitFileBtn) ?>
		</div>
	</div>