<?php

	$title = null;
	$eventName = array(
		"name" => "event_name",
		"id" => "event_name",	
		"type" => "text",
		"class" => "form-control",
		"required" => "required"
	);

	$eventNatureValue = array();

	$place = array(
		"name" => "place",
		"id" => "place",	
		"type" => "text",
		"class" => "form-control"
	);

	$promotingInstitution = array(
		"name" => "promoting_institution",
		"id" => "promoting_institution",	
		"type" => "text",
		"class" => "form-control"
	);			
	
	$startDate = array(
		"name" => "start_date",
		"id" => "start_date",	
		"type" => "text",
		"class" => "form-control"
	);	

	$endDate = array(
		"name" => "end_date",
		"id" => "end_date",	
		"type" => "text",
		"class" => "form-control"
	);	
		
?>

<div id="addEventParticipationForm" class="collapse">
	<h4> Nova Participação em Evento</h4>
	<hr>
	<div class="row">

		<div class="col-lg-10">
			<?= form_open("save_event_participation") ?>
				<div class="header"></div>

				<?php include '_event_form.php'; ?>
				
			<?= form_close() ?>
			<br><br><br>

		</div>
	</div>
</div>

