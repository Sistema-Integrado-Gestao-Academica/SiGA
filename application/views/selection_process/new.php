
<?php require_once (APPPATH."/constants/SelectionProcessConstants.php");  ?>

<h2 class="principal">Novo Processo Seletivo para o curso <b><i><?php echo $course['course_name'];?></i></b> </h2>

<?php
	
	$studentType = array(
		SelectionProcessConstants::REGULAR_STUDENT => 'Alunos Regulares',
		SelectionProcessConstants::SPECIAL_STUDENT => 'Alunos Especiais'
	);

	$startDate = array(
	    "name" => "selective_process_start_date",
	    "id" => "selective_process_start_date",
	    "type" => "text",
		"placeholder" => "Informe a data inicial",
	    "class" => "form-campo",
	    "class" => "form-control"
	);

	$endDate = array(
	    "name" => "selective_process_end_date",
	    "id" => "selective_process_end_date",
	    "type" => "text",
		"placeholder" => "Informe a data final",
	    "class" => "form-campo",
	    "class" => "form-control"
	);

	$name = array(
		"name" => "selective_process_name",
		"id" => "selective_process_name",
		"type" => "text",
		"class" => "form-campo form-control",
		"placeholder" => "Informe o nome do edital",
		"maxlength" => "60"
	);

	$noticeFile = array(
		"name" => "notice_file",
		"id" => "notice_file",
		"type" => "file"
		// "class" => "form-campo form-control"
	);

	$submitBtn = array(
		"id" => "open_selective_process_btn",
		"class" => "btn bg-primary btn-flat",
		"content" => "Abrir Processo Seletivo",
		"type" => "submit"
	);

	$hidden = array(
		"course" => $course['id_course']
	);
?>

<!-- Basic data of selection process -->

<h4><i class="fa fa-file-o"></i> Dados básicos</h4>
<br>

<?= form_open_multipart("selectiveprocess/newSelectionProcess"); ?>


<div class="row">
	<div class="col-md-3">
		<?= form_label("Processo Seletivo para:", "student_type"); ?>
		<?= form_dropdown("student_type", $studentType, "id='student_type'"); ?>
	</div>
	<div class="col-md-6">
		<?= form_label("Nome do edital", "selective_process_name"); ?>
		<?= form_input($name); ?>
	</div>
</div>

<br>
<br>
<?= form_label("PDF do edital <small><i>(Arquivo PDF apenas)</i></small>:", "notice_file"); ?>
<?= form_input($noticeFile); ?>

<br>
<br>

<!-- Applying period of selection process -->

<h4><i class="fa fa-calendar"></i> Período de inscrições</h4>
<br>

<div class="row">
	<div class="col-md-3">
		<?= form_label("Data de início do edital", "selective_process_start_date"); ?>
		<?= form_input($startDate); ?>
	</div>
	<div class="col-md-3">
		<?= form_label("Data final do edital", "selective_process_end_date"); ?>
		<?= form_input($endDate); ?>
	</div>
</div>

<!-- Selection Process Settings -->
<br>
<br>
<h4><i class="fa fa-cogs"></i> Configurações do edital</h4>



<br>
<br>
<div class="row">
	<div class="col-md-3">
		<?= form_button($submitBtn) ?>
	</div>
</div>



<?= form_close(); ?>