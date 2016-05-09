
<br>
<?= 
	callout(
		"warning",
		"Informe sua matrícula logo abaixo",
		"<b>{$userName}</b>, Você deve informar a matrícula que lhe foi dada para prosseguir com o uso do sistema.<br>Informe somente os números da matrícula.");

	echo "<h3><i class='fa fa-exclamation'></i> Informar matrícula</h3>";

	$enrollment = array(
		"name" => "student_enrollment",
		"id" => "student_enrollment",
		"type" => "text",
		"class" => "form-control",
		"placeholder" => "Informe sua matrícula",
		"maxlength" => "9",
		"autofocus" => TRUE,
		"aria-describedby" => "input_btn"
	);

	$submitBtn = array(
		"id" => "inform_enrollment_btn",
		"class" => "btn btn-primary btn-flat",
		"content" => "Salvar matrícula",
		"type" => "submit"
	);

	$hidden = array(
		'course' => $courseId,
		'student' => $studentId
	);

?>

<div class="form-group">
<?= form_open("student/registerEnrollment"); ?>

	<?= form_hidden($hidden); ?>

	<?= form_label("Matrícula", "student_enrollment"); ?>
<div class="row">
<div class="col-md-6">
	
<div class="input-group">
	<?= form_input($enrollment); ?>
	<span class="input-group-addon" id="input_btn">
		<?= form_button($submitBtn);?>
	</span>
</div>

</div>
</div>
<?= form_close(); ?>
</div>
