<script src=<?=base_url("js/program.js")?>></script>
<?php $programName = $program['acronym'];?>
<h2 class="principal">Informações no portal para o <?= $programName?></h2>
<?php

$programId = $program['id_program'];

echo "<h4> <a href='#add_field_form' data-toggle='collapse'><i class = 'fa fa-plus-circle'></i> Adicionar informação </a></h4>";

$title = array(
		"name" => "title",
		"id" => "title",	
		"type" => "text",
		"required" => TRUE,
		"placeholder" => "Título da informação",
		"class" => "form-control"
);	

$details = array(
	"name" => "details",
	"id" => "details",	
	"type" => "text",
	"placeholder" => "Texto que irá aparecer como detalhe da informação",
	"class" => "form-control"
);	

$hidden = array(
	"id" => "program_id",
	"name" => "program_id",
	"type" => "hidden",
	"value" => $programId
);

echo "<div id='add_field_form' class='collapse'>";

	echo form_label("Título", "title_label");
	echo form_input($title);

	echo "<br>";

	echo form_label("Detalhes/Descrição", "details");
	echo form_textarea($details);

	echo form_input($hidden);

	echo "<br>";

	echo "<div class='col-lg-3'>";
	echo "</div>";
	echo "<div class='col-lg-6'>";
	 	echo form_button(array(
		    "id" => "add_info_btn",
		    "class" => "btn bg-olive btn-block",
		    "content" => "Adicionar informação",
		    "type" => "submit"
		));
	echo "</div>";
	echo "<br>";
	echo "<hr>";

echo "</div>";

echo "<div id='add_result'>";

showExtraInfo($extraInfo);