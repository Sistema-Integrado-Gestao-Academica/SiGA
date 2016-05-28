<h2 class='principal'>Matricular alunos no curso <i><b><?=$course['course_name']?></b></i></h2>
<?php

	$guestName = array(
		"name" => "guest_name",
		"id" => "guest_name",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "70",
		"placeholder" => "Nome do usuário a pesquisar"
	);

	$searchForStudentBtn = array(
		"id" => "search_guests_btn",
		"class" => "btn btn-success btn-flat",
		"content" => "Procurar por aluno",
		"type" => "submit"
	);

	$hidden = array(
		'id' => "course",
		'name' => "course",
		'type' => "hidden",
		'value' => $course['id_course']
	);

	echo form_input($hidden);
?>
	<div class='row'>
	<div class='col-lg-6'>
		<?= form_label("Informe o nome do usuário: ", "student_name"); ?>
		<div class='input-group'>
			<?= form_input($guestName); ?>
			<span class="input-group-addon"> <?= form_button($searchForStudentBtn);?> </span>
		</div>
	</div>
	</div>

<div id="guests_table">
<!-- <div id="guests_table_known"> -->
	
<?php
if($courseGuests !== FALSE){
	echo "<h3><i class='fa fa-users'></i> Lista de Usuários que podem ser matriculados:</h3><br>";

	displayGuestForEnrollment($courseGuests, $course['id_course']);	
}
else{
	callout("info", "Não existem usuários disponíveis para matrícula no momento.");
}
?>

</div>
