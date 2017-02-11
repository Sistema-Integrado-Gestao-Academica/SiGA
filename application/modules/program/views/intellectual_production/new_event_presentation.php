
<?php

	$title = array(
		"name" => "title",
		"id" => "title",	
		"type" => "text",
		"class" => "form-control",
		"required" => "required"
	);

	$eventNatureValue = array();
	$presentationNatureValue = array();
		
?>

<div id="addEventPresentationForm" class="collapse">

	<div class="row">

		<div class="col-lg-10">
			<?= form_open("save_event_presentation") ?>
				<div class="header"></div>
				
				<?php include '_event_form.php'; ?>
				

			<?= form_close() ?>
			<br><br><br>

		</div>
	</div>
</div>

