
<br>
<br>
<br>
<br>

<?php

echo "<h2>Matricular alunos no curso <i>".$courseName."</i></h2><br><br>";

displayEnrollStudentForm();

?>

<br>
<br>

<div class='form-group col-xs-6'>
	<?php
	echo form_open('course/enrollStudent');

		echo form_hidden('courseId', $courseId);
		echo form_hidden('courseType', $courseType);

		if($courseType == "academic_program"){

			echo "<h4><span class='label label-primary'>".$courseName." - Programa acadêmico</span></h4><br>";
			echo form_label("Matricular no curso: ");
			echo "<br>";

			$courseDropdown = array();
			if($masterDegree !== FALSE){
				
				$courseDropdown = array(
					'master_degree' => $masterDegree['master_degree_name']." - Mestrado Acadêmico"
				);

				if($doctorate !== FALSE){
					$courseDropdown['doctorate'] = $doctorate['doctorate_name']." - Doutorado Acadêmico";
				}
			}

			echo form_dropdown("program_dropdown", $courseDropdown, "", "id = 'program_dropdown' class='form-control'");
			echo "<br>";
	
		}else if($courseType == "professional_program"){
			
			echo "<h4><span class='label label-primary'>".$courseName." - Programa profissional</span></h4><br>";
			echo form_label("Matricular no curso: ");
			echo "<br>";

			$courseDropdown = array();
			if($masterDegree !== FALSE){
				
				$courseDropdown = array(
					'master_degree' => $masterDegree['master_degree_name']." - Mestrado Profissional"
				);
			}

			echo form_dropdown("program_dropdown", $courseDropdown, "", "id = 'program_dropdown' class='form-control'");
			echo "<br>";
		}
	?>

		<div id="search_student_result"></div>
			
	<?php
	echo form_close();
	?>
</div>