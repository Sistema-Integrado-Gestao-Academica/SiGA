
<h2 class="principal">Lista de alunos do curso <i><?php echo $course['course_name']?></i> </h2>

<?php 
	
	require_once(MODULESPATH."secretary/domain/StudentRegistration.php");

	if($students !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Matrícula</th>
			        <th class="text-center">Aluno</th>
			        <th class="text-center">E-mail</th>
			        <th class="text-center">Data de matrícula</th>
			        <th class="text-center">Status</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($students as $student){

						echo "<tr>";
				    		echo "<td>";
				    			$registration = $student['enrollment'];
				    			
				    			if($registration !== NULL){
					    			echo bold("Matrícula atual: ").$registration;
				    			}else{
				    				echo "<span class='label label-danger'> Matrícula não informada ainda.</span>";
				    			}

				    			echo "<hr>";

					    		echo form_open("secretary/enrollment/updateStudentRegistration");

					    			echo form_hidden(array(
					    				'course' => $course['id_course'],
					    				'student' => $student['id']
					    			));

					    			echo "<div class='row'>";
					    			echo "<div class='col-md-10'>";
					    			echo "<div class='input-group'>";

						    			echo form_input(array(
						    				'id' => "new_registration",
						    				'name' => "new_registration",
						    				'type' => "text",
						    				'class' => "form-campo form-control",
						    				'placeholder' => "Nova matrícula",
						    				'maxlength' => StudentRegistration::REGISTRATION_LENGTH
						    			));

						    			echo "<span class='input-group-addon'>";
						    			echo form_button(array(
						    				'id' => "update_registration",
						    				'type' => "submit",
						    				'class' => "btn btn-success btn-flat",
						    				'content' => "Atualizar matrícula"
						    			));
						    			echo "</span>";

					    			echo "</div>";
					    			echo "</div>";
					    			echo "</div>";

					    		echo form_close();

				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['email'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['enroll_date'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $student['status'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo "</td>";
			    		echo "</tr>";
			    	}
?>
			</tbody>
		</table>
		</div>

<?php
 	} else{
?>
	<div class="callout callout-info">
		<h4>Nenhum aluno matriculado neste curso.</h4>
	</div>
<?php }?>

<?= anchor('secretary/secretary/coursesStudents', 'Voltar', "class='btn btn-danger'")?>