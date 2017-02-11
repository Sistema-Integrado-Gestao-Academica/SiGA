<script src=<?=base_url("js/production.js")?>></script>
<?php

	$title = NULL;
	$eventNatureValue = array_search($eventProduction['event_nature'], $eventNatures);

	$eventName = array(
		"name" => "event_name",
		"id" => "event_name",	
		"type" => "text",
		"class" => "form-control",
		"required" => "required",
		"value" => $eventProduction['event_name']
	);

	$place = array(
		"name" => "place",
		"id" => "place",	
		"type" => "text",
		"class" => "form-control",
		"value" => $eventProduction['place']
	);

	$promotingInstitution = array(
		"name" => "promoting_institution",
		"id" => "promoting_institution",	
		"type" => "text",
		"class" => "form-control",
		"value" => $eventProduction['promoting_institution']
	);			
	
	$startDateValue = $eventProduction['start_date'];
	if($startDateValue == "0000-00-00"){
		$startDateValue = NULL; 
	}
	else{
		$startDateValue = convertDateTimeToDateBR($startDateValue);
		$startDateValue = htmlspecialchars($startDateValue);
	}
	
	$startDate = array(
		"name" => "start_date",
		"id" => "start_date",	
		"type" => "text",
		"class" => "form-control",
		"value" => $startDateValue
	);	

	$endDateValue = $eventProduction['end_date'];
	if($endDateValue == "0000-00-00"){
		$endDateValue = NULL; 
	}
	else{
		$endDateValue = convertDateTimeToDateBR($endDateValue);
		$endDateValue = htmlspecialchars($endDateValue);
	}
	$endDate = array(
		"name" => "end_date",
		"id" => "end_date",	
		"type" => "text",
		"class" => "form-control",
		"value" => htmlspecialchars($endDateValue)
	);	
?>

<div id="form" align="center">

	<div class="row">

		<div class="col-lg-12">
			<?= form_open("update_event_participation") ?>
				
				<div class="principal"><h2>Editar Participação em Evento</h2></div>
				<?php include '_event_form.php'; ?>
				
				<?= form_hidden('id', $eventProduction['id']); ?>

			
			<?= form_close() ?>
			<br>
			<div class="col-lg-7">

				<div class="col-lg-3">
					<?= anchor("intellectual_production", 'Voltar', "class='btn btn-danger btn-block'") ?>
				</div>
				
			</div>
		</div>
	</div>

</div>