<br>
<h4>Olá, <b><?=$user->getName();?> </b>
<br><br>
</h4>

	<div class="form-box">
	<div class="header">
		<span class="fa fa-graduation-cap"> Escolha um curso</span>
	</div>
	<?= form_open("auth/userController/courseForGuest") ?>
		<div class="body bg-gray">
			<div class="form-group">
				<?= form_label("Cursos ", "course_name") ?>
				<?= form_dropdown("courses_name", $coursesName) ?>
				<?= form_error("course_name") ?>
			</div>
		</div>

		<div class="footer">
			<?= form_button(array(
				"class" => "btn bg-olive btn-block",
				"content" => "Solicitar inscrição",
				"type" => "submit"
			)) ?>
		</div>	
	<?= form_close() ?>
	</div>



