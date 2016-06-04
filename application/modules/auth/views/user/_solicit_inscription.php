<div class="form-box">

<?php 
	if($courseGuest == FALSE){ ?>
		<div class="callout callout-green">
	    	<h4>Olá, <b><?=$user->getName();?> </b>
			<h4> Solicite aqui a inscrição em seu curso </h4>
		</div>
<?php 
	} 
	?>
	<div class="header">
		<span class="fa fa-graduation-cap"> Escolha um curso</span>
	</div>
	
	<?php if($is_ajax){
		
		$button['id'] = "solicit_inscription";
	}
	else{
		echo form_open("secretary/enrollment/newCourseForGuest");
	} ?>
	<div class="body bg-gray">
		<div class="form-group">
			<?= form_label("Cursos ", "course_name") ?>
			<?= form_dropdown("courses_name", $coursesName, '#', 'id="courses_name"','name="courses_name"') ?>
			<?= form_error("course_name") ?>

		</div>
	</div>

	<div class="footer">
		<?= form_button($button) ?>
</div>	
	<?= form_close() ?>
	</div>

