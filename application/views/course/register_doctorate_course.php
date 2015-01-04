
<div class="row">

	<div class="col-lg-6">	
		<br>
		<?php
		echo form_open("course/registerDoctorateCourse");
		
			/*
			 * Course id passed through the loadTemplate on 
			 *  formToCreateDoctorateCourse() method on course controller.
			*/
			echo form_hidden('course_id', $course_id);
			
			formToCreateDoctorateCourse();

		echo form_close();
		?>
	</div>

</div>