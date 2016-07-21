<script src=<?=base_url("js/production.js")?>></script>
<?php
	
	$production = $production[0];

	$title = array(
		"name" => "title",
		"id" => "title",	
		"type" => "text",
		"class" => "form-control",
		"required" => "required",
		"value" => $production->getTitle()
	);

	$year = array(
		"name" => "year",
		"id" => "year",	
		"type" => "text",
		"class" => "form-control",
		"value" => $production->getYear()
	);

	$typeValue = $production->getType();
	$subtypeValue = $production->getSubtype();
	$projectValue = $production->getProject();

	$periodic = array(
		"name" => "periodic",
		"id" => "periodic",	
		"type" => "text",
		"class" => "form-control",
		"value" => $production->getPeriodic()

	);

	$identifier = array(
		"name" => "identifier",
		"id" => "identifier",	
		"type" => "text",
		"class" => "form-control",
		"value" => $production->getIdentifier()
	);			
	
	$qualis = array(
		"name" => "qualis",
		"id" => "qualis",	
		"type" => "text",
		"class" => "form-control",
		"readonly" => "readonly",
		"value" => $production->getQualis()
	);				

	$productionId = $production->getId();	
?>

<div id="form" align="center">

	<div class="row">

		<div class="col-lg-12">
			<?= form_open("update_production") ?>
				
				<div class="principal"><h2>Editar produção</h2></div>
				<?php include '_intellectual_production_form.php'; ?>
				
				<?= form_hidden('id', $productionId); ?>

			
			<?= form_close() ?>
			<br>
			<div class="col-lg-7" id="center_btn_form">

				<div class="col-lg-3">
					<?= anchor("intellectual_production", 'Voltar', "class='btn btn-danger btn-block'") ?>
				</div>
				
				<div class="col-lg-5">

					<?= anchor("edit_coauthors/{$productionId}", 'Editar autores', "class='btn btn-primary btn-block'") ?>
				</div>
			</div>
		</div>
	</div>

</div>
