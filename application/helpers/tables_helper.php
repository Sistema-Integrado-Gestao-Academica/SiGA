<?php

require_once(APPPATH."/controllers/course.php");
require_once(APPPATH."/controllers/offer.php");

function courseTableToSecretaryPage($courses, $masterDegrees, $doctorates){
	echo "<div class=\"box-body table-responsive no-padding\">";
	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
		    echo "<tr>";
		        echo "<th class=\"text-center\">ID</th>";
		        echo "<th class=\"text-center\">Curso</th>";
		        echo "<th class=\"text-center\">Tipo</th>";
		        echo "<th class=\"text-center\">Ações</th>";
		    echo "</tr>";

		    	foreach($courses as $courseData){
		    		$courseType = $courseData['course_type'];
		    		$courseId = $courseData['id_course'];

		    		switch($courseType){
		    			case "academic_program":

		    				$thereIsMasterDegree = array_key_exists($courseId, $masterDegrees);
		    				$thereIsDoctorate = array_key_exists($courseId, $doctorates);
		    				
		    				if($thereIsMasterDegree){

		    					if($thereIsDoctorate){

		    						echo "<tr>";
							    		echo "<td align='center' rowspan=\"3\">";
							    		echo $courseId;
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-primary\">Programa Acadêmico</span>";
							    		echo "</td>";

							    		echo "<td>";
							    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
							    		echo "</td>";
						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-success\">Mestrado Acadêmico</span>";
							    		echo "</td>";

						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$doctorates[$courseId]['doctorate_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-warning\">Doutorado Acadêmico</span>";
							    		echo "</td>";
						    		echo "</tr>";

		    					}else{

				    				echo "<tr>";
							    		echo "<td align='center' rowspan=\"2\">";
							    		echo $courseId;
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-primary\">Programa Acadêmico</span>";
							    		echo "</td>";
						    		
							    		echo "<td>";
							    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
							    		echo "</td>";
						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-success\">Mestrado Acadêmico</span>";
							    		echo "</td>";

						    		echo "</tr>";

		    					}
		    				}

		    				break;

		    			case "professional_program":
		    				$thereIsMasterDegree = array_key_exists($courseId, $masterDegrees);
		
							if($thereIsMasterDegree){

			    				echo "<tr>";
						    		echo "<td align='center' rowspan=\"2\">";
						    		echo $courseId;
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<span class=\"label label-primary\">Programa Profissional</span>";
						    		echo "</td>";
					    		
						    		echo "<td>";
						    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
						    		echo "</td>";
					    		echo "</tr>";

					    		echo "<tr>";
						    		echo "<td>";
						    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<span class=\"label label-success\">Mestrado Profissional</span>";
						    		echo "</td>";

					    		echo "</tr>";
							}

		    				break;

		    			default:
				    		echo "<tr>";
				    		echo "<td>";
				    		echo $courseId;
				    		echo "</td>";

				    		echo "<td>";
				    		echo $courseData['course_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		if($courseType == "graduation"){
							    echo "<span class=\"label label-primary\">Graduação</span>";
				    		}else if($courseType == "ead"){
							   	echo "<span class=\"label label-primary\">EAD</span>";
				    		}else{
				    			echo "-";
				    		}
				    		echo "</td>";

				    		echo "<td>";
				    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
				    		echo "</td>";
				    		
				    		echo "</tr>";
		    				break;
		    		}
		    	}
		    
		echo "</tbody>";
	echo "</table>";
echo "</div>";

}

function displayOffersList($offers){

	define("PROPOSED", "proposed");
	define("APPROVED", "approved");

	$course = new Course();
	
	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Curso</th>";
			        echo "<th class=\"text-center\">Lista de Oferta</th>";
			        echo "<th class=\"text-center\">Status</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    foreach($offers as $courseName => $offer){
			    	
			    	$foundCourse = $course->getCourseByName($courseName);
					$courseId = $foundCourse['id_course'];

			    	echo "<tr>";

			    		echo "<td>";
			    			echo $courseName;
			    		echo "</td>";

			    		if($offer !== FALSE){

			    			switch($offer['offer_status']){
								case PROPOSED:
									$status = "Proposta";
									break;

								case APPROVED:
									$status = "Aprovada";
									break;

								default:
									$status = "-";
									break;
							}

				    		echo "<td>";
				    			echo $offer['id_offer'];
				    		echo "</td>";
				    		
				    		echo "<td>";
				    			echo $status;
				    		echo "</td>";

				    		echo "<td>";
		    					echo "<div class=\"callout callout-info\">";
				    			if($offer['offer_status'] === PROPOSED){
									echo "<h4>Editar</h4>";
			    					
			    					echo anchor("offer/displayDisciplines/{$offer['id_offer']}/{$courseId}","<i class='fa fa-edit'></i>", "class='btn btn-danger'");
								    echo "<p> <b><i>Aqui é possível adicionar disciplinas a lista de oferta.</i><b/></p>";
				    			}else{
			    					echo anchor("", "<i class='fa fa-edit'></i>", "class='btn btn-danger disabled'");
								    echo "<p> <b><i>Somente as listas de ofertas com status \"proposta\" podem ser alteradas.</i><b/></p>";
				    			}
								echo "</div>";
				    		echo "</td>";

			    		}else{

			    			echo "<td colspan=3>";
		    					echo "<div class=\"callout callout-info\">";
									echo "<h4>Nenhuma lista de ofertas proposta para o semestre atual.</h4>";
							    	echo anchor("offer/newOffer/{$courseId}", "Nova Lista de Ofertas", "class='btn btn-primary'");
								    echo "<p> <b><i>OBS.: A lista de oferta será criada para o semestre atual.</i><b/></p>";
								echo "</div>";
			    			echo "</td>";
			    		}

			    	echo "</tr>";
			    }
		
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayOfferDisciplines($idOffer, $course, $disciplines){

	echo "<h3>Lista de Oferta</h3>";
	echo "<h3><b>Curso</b>: ".$course['course_name']."</h3>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código da Lista: ".$idOffer."</th>";
			        echo "<th class=\"text-center\">Status: Proposta</th>";
			    echo "</tr>";

			    echo "<tr>";
			    	echo "<td colspan=2>";
			    	echo "<b>Disciplinas</b>";
			    	echo "</td>";
			    echo "</tr>";

			    if($disciplines !== FALSE){

				    foreach($disciplines as $discipline){
					    
					    echo "<tr>";
					    	echo "<td colspan=2>";
				    		echo $discipline['discipline_code']." - ".$discipline['discipline_name']."(".$discipline['name_abbreviation'].")";
					    	echo "</td>";
					    echo "</tr>";
				    }

				    echo "<tr>";
						echo "<td colspan=2>";
		                echo anchor("offer/addDisciplines/{$idOffer}/{$course['id_course']}",'Adicionar disciplinas', "class='btn btn-primary'");
		                echo "</td>";
				    echo "</tr>";
			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=2>";
						    	echo "<div class=\"callout callout-info\">";
	                            	echo "<h4>Nenhuma disciplina adicionada a essa lista de oferta no momento.</h4>";

	                            	echo anchor("offer/addDisciplines/{$idOffer}/{$course['id_course']}",'Adicionar disciplinas', "class='btn btn-primary'");
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayRegisteredDisciplines($allDisciplines, $course, $idOffer){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código: </th>";
			        echo "<th class=\"text-center\">Sigla</th>";
			        echo "<th class=\"text-center\">Disciplina</th>";
			        echo "<th class=\"text-center\">Créditos</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($allDisciplines !== FALSE){

				    foreach($allDisciplines as $discipline){
					    
					    $offer = new Offer();
			    		$disciplineAlreadyExistsInOffer = $offer->disciplineExistsInOffer($discipline['discipline_code'], $idOffer);

					    echo "<tr>";
					    	echo "<td>";
				    			echo $discipline['discipline_code'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $discipline['name_abbreviation'];
					    	echo "</td>";
					    	
					    	echo "<td>";
					    		echo $discipline['discipline_name'];
					    	echo "</td>";
					    	
					    	echo "<td>";
					    		echo $discipline['credits'];
					    	echo "</td>";

					    	echo "<td>";
					    		if($disciplineAlreadyExistsInOffer){
					    			echo anchor("offer/addDisciplineToOffer/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "Já adicionada ao curso ".$course['course_name'], "class='btn btn-primary disabled'");
					    		}else{
					    			echo anchor("offer/addDisciplineToOffer/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "Adicionar ao curso ".$course['course_name'], "class='btn btn-primary'");
					    		}
					    	echo "</td>";

					    echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=5>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Não há disciplinas cadastradas no momento.</h4>";
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayRegisteredStudents($students, $studentNameToSearch){

	$thereIsStudents = sizeof($students) > 0;

	if($thereIsStudents){

		$enrollStudentBtn = array(
			"id" => "enroll_student_btn",
			"class" => "btn bg-olive btn-block",
			"content" => "Matricular aluno",
			"type" => "submit",
			"style" => "width:35%"
		);
	
		echo form_label("Usuários encontrados:","user_to_enroll");
		echo "<h4><small>OBS.: Usuários pertencentes ao grupo convidado apenas.</small></h4>";
		echo form_dropdown('user_to_enroll', $students, "", "id = user_to_enroll class='form-control'");

		echo "<br>";
		echo form_button($enrollStudentBtn);
		
	}else{
		echo "<div class=\"callout callout-info\">";
			echo "<h4>Nenhum aluno encontrado com a chave '".$studentNameToSearch."'.<br><small>OBS.: Usuários pertencentes ao grupo convidado apenas.</small></h4>";
		echo "</div>";
	}
}