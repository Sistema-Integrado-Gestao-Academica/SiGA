<div class="row">

	<div class="col-lg-6">

		<?php if($title != null){ ?>

		<div class="form-group">
			<?=form_label("Título do trabalho", "title")?>
			<?=form_input($title)?>
		</div>
		<div class="form-group">
			<?=form_label("Natureza da apresentação", "presentation_nature")?>
			<?= form_dropdown("presentation_nature", $presentationNatures, $presentationNatureValue, ['class' => "form-control", 'id' => "presentation_nature"]) ?>
		</div>
		<?php } ?>

		<div class="form-group">
			<?= form_label("Nome do Evento", "event_name") ?>
			<?= form_input($eventName)?>
		</div>


		<div class="form-group">
			<?= form_label("Local de Realização", "place") ?>
			<?= form_input($place)?>
		</div>

		<?php if($title == null){ ?>
			<div class="form-group">
				<?= form_label("Natureza do Evento", "event_nature") ?>
				<?= form_dropdown("event_nature", $eventNatures, $eventNatureValue, ['class' => "form-control", 'id' => "event_nature"]) ?>
			</div>
		<?php } ?>
	</div>
	<div class="col-lg-6">
		<?php if($title != null){ ?>
			<div class="form-group">
				<?= form_label("Natureza do Evento", "event_nature") ?>
				<?= form_dropdown("event_nature", $eventNatures, $eventNatureValue, ['class' => "form-control", 'id' => "event_nature"]) ?>
			</div>
		<?php } ?>

		<div class="form-group">
			<?= form_label("Instituição promotora", "promoting_institution") ?>
			<?= form_input($promotingInstitution)?>
		</div>

		<div class="form-group">
			<?= form_label("Período de Realização", "identifier") ?>
		<div class="row">
			<div class="col-lg-6">
				<?= form_input($startDate)?> 
			</div>
			<div class="col-lg-6">
				<?= form_input($endDate)?>					
			</div>
		</div>
		</div>
		
	</div>	
		<div class="footer">
			<div class="row">
				<div class="col-lg-5" id="center_btn_form">
					<?= form_button(array(
						"class" => "btn bg-olive btn-block",
						"type" => "submit",
						"content" => "Salvar"
					)) ?>
				</div>
			</div>
		</div>
	</div>
	
	