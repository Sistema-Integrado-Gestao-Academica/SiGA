<?php

require_once(APPPATH."/controllers/course.php");
require_once(APPPATH."/controllers/offer.php");
require_once(APPPATH."/controllers/syllabus.php");
require_once(APPPATH."/controllers/usuario.php");
require_once(APPPATH."/controllers/module.php");

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

function displayCourseSyllabus($syllabus){
	$course = new Course();
	
	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Curso</th>";
			        echo "<th class=\"text-center\">Código Currículo</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    foreach($syllabus as $courseName => $syllabus){
			    	
			    	$foundCourse = $course->getCourseByName($courseName);
					$courseId = $foundCourse['id_course'];

			    	echo "<tr>";

			    		echo "<td>";
			    			echo $courseName;
			    		echo "</td>";

			    		if($syllabus !== FALSE){

			    			echo "<td>";
			    				echo $syllabus['id_syllabus'];
			    			echo "</td>";

			    			echo "<td>";
			    				echo "<div class=\"callout callout-info\">";
									echo "<h4>Editar</h4>";		    					
			    					echo anchor("syllabus/displayDisciplinesOfSyllabus/{$syllabus['id_syllabus']}/{$courseId}","<i class='fa fa-edit'></i>", "class='btn btn-danger'");
								    echo "<p> <b><i>Aqui é possível adicionar e retirar disciplinas ao currículo do curso.</i><b/></p>";
								echo "</div>";
			    			echo "</td>";

			    		}else{
							echo "<td colspan=2>";
		    					echo "<div class=\"callout callout-info\">";
									echo "<h4>Nenhum currículo cadastrado para esse curso.</h4>";
							    	echo anchor("syllabus/newSyllabus/{$courseId}", "Novo Currículo", "class='btn btn-primary'");
								echo "</div>";
			    			echo "</td>";			    			
			    		}

			    	echo "</tr>";
			    }
		
			echo "</tbody>";
		echo "</table>";
	echo "</div>";	
}

function displaySyllabusDisciplines($syllabusId, $syllabusDisciplines){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Disciplinas</th>";
			    echo "</tr>";

			    if($syllabusDisciplines !== FALSE){

			    	foreach($syllabusDisciplines as $discipline){
			    		
			    	}

			    }else{

			    	echo "<tr>";
			    		echo "<td>";
			    			echo "<div class=\"callout callout-info\">";
								echo "<h4>Nenhuma disciplina adicionada ao currículo.</h4>";
							   	echo anchor("syllabus/addDisciplines/{$syllabusId}", "Adicionar disciplinas", "class='btn btn-primary'");
							echo "</div>";
			    		echo "</td>";
			    	echo "</tr>";
			    }
			    
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayDisciplinesToSyllabus($syllabusId, $allDisciplines){

	echo "<h4>Lista de disciplinas:</h4>";
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
					    
					    $syllabus = new Syllabus();
			    		$disciplineAlreadyExistsInSyllabus = $syllabus->disciplineExistsInSyllabus($discipline['discipline_code'], $syllabusId);

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
					    		if($disciplineAlreadyExistsInSyllabus){
					    			echo anchor("syllabus/removeDisciplineFromSyllabus/{$syllabusId}/{$discipline['discipline_code']}", "Remover disciplina", "class='btn btn-danger'");
					    		}else{
					    			echo anchor("syllabus/addDisciplineToSyllabus/{$syllabusId}/{$discipline['discipline_code']}", "Adicionar disciplina", "class='btn btn-primary'");
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
								    echo "<p> <b><i>Aqui é possível adicionar disciplinas a lista de oferta e aprová-la.</i><b/></p>";
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

	echo "<div class=\"row\">";
		echo "<div class=\"col-xs-3\">";
			if($disciplines !== FALSE){

				echo anchor("offer/approveOfferList/{$idOffer}", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
		             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		             data-content=\"OBS.: Ao aprovar a lista de oferta não é possível adicionar ou retirar disciplinas.\"");
			}else{
				echo anchor("", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
		             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\" disabled='true'
		             data-content=\"Não é possível aprovar uma lista sem disciplinas.\"");
			}
		echo "</div>";
		echo "<div class=\"col-xs-3\">";
			echo anchor("usuario/secretary_offerList", "Voltar", "class='btn btn-danger'");
		echo "</div>";
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
					    			echo anchor("offer/removeDisciplineFromOffer/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "Remover do curso ".$course['course_name'], "class='btn btn-danger'");
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

function displayRegisteredUsers($allUsers){
	
	echo "<h3>Lista de Usuários:</h3>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código</th>";
			        echo "<th class=\"text-center\">Nome</th>";
			        echo "<th class=\"text-center\">CPF</th>";
			        echo "<th class=\"text-center\">E-mail</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($allUsers !== FALSE){

				    foreach($allUsers as $user){
				    	
				    	echo "<tr>";

					    	echo "<td>";
					    		echo $user['id'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $user['name'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $user['cpf'];
					    	echo "</td>";

					    	echo "<td>";
					    	 	echo $user['email'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo anchor("usuario/manageGroups/{$user['id']}", "<i class='fa fa-group'></i> Gerenciar Grupos", "class='btn btn-primary'");
					    	echo "</td>";

				    	echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=5>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Não há usuários cadastradas no momento.</h4>";
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayUserGroups($idUser, $userGroups){
	
	$user = new Usuario();
	$foundUser = $user->getUserById($idUser);
	echo "<h3>Grupos pertencentes a <b>".$foundUser['name']."</b>:</h3>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Grupo</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($userGroups !== FALSE){

				    foreach($userGroups as $group){
				    	
				    	echo "<tr>";

					    	echo "<td>";
					    		echo $group['group_name'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo anchor("usuario/removeUserGroup/{$idUser}/{$group['id_group']}", "Remover Grupo", "class='btn btn-danger'");
					    	echo "</td>";

				    	echo "</tr>";
				    }

			    }else{
			    	echo "<tr>";
				    	echo "<td colspan=2>";
					    	echo "<div class=\"callout callout-warning\">";
                            	echo "<h4>Não há grupos cadastrados para esse usuário.</h4>";
                            echo "</div>";
				    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayAllGroupsToUser($idUser, $allGroups, $userGroups){

	$user = new Usuario();
	$foundUser = $user->getUserById($idUser);

	echo "<h3>Grupos Existentes:</h3>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Grupo</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($allGroups !== FALSE){

				    foreach($allGroups as $idGroup => $groupName){
				    	
				    	$alreadyHaveThisGroup = FALSE;
				    	if($userGroups !== FALSE){

					    	foreach($userGroups as $group){
					    		if($idGroup == $group['id_group']){
				    				$alreadyHaveThisGroup = TRUE;
				    				break;
					    		}
					    	}
				    	}else{
				    		$alreadyHaveThisGroup = FALSE;
				    	}

				    	echo "<tr>";

					    	echo "<td>";
					    		echo $groupName;
					    	echo "</td>";

					    	echo "<td>";
					    		if($alreadyHaveThisGroup){
				    				echo anchor("", "<i class='fa fa-plus'></i> <i class='fa fa-user'></i> <b>".$foundUser['name']."</b>", "class='btn btn-primary disabled'");
					    		}else{
				    				echo anchor("usuario/addGroupToUser/{$idUser}/{$idGroup}", "<i class='fa fa-plus'></i> <i class='fa fa-user'></i> <b>".$foundUser['name']."</b>", "class='btn btn-primary'");
					    		}
					    	echo "</td>";

				    	echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=2>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Não há grupos cadastrados no sistema no momento.</h4>";
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayRegisteredGroups($allGroups){
	echo "<h3>Grupos Cadastrados:</h3>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Grupo</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($allGroups !== FALSE){

				    foreach($allGroups as $idGroup => $groupName){

				    	echo "<tr>";

					    	echo "<td>";
					    		echo $groupName;
					    	echo "</td>";

					    	echo "<td>";
					    		echo anchor("usuario/listUsersOfGroup/{$idGroup}", "<i class='fa fa-list-ol'></i> Listar usuários", "class='btn btn-primary' style='margin-right:5%;'");
					    		echo anchor("usuario/removeAllUsersOfGroup/{$idGroup}", "<i class='fa fa-eraser'></i> Remover todos usuários do grupo", "class='btn btn-danger'");
					    	echo "</td>";

				    	echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=2>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Não há grupos cadastrados no sistema no momento.</h4>";
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayUsersOfGroup($idGroup, $usersOfGroup){
	
	$group = new Module();
	$foundGroup = $group->getGroupById($idGroup);
	echo "<h3>Usuários do grupo <b>".$foundGroup['group_name']."</b>:</h3>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código</th>";
			        echo "<th class=\"text-center\">Nome</th>";
			        echo "<th class=\"text-center\">CPF</th>";
			        echo "<th class=\"text-center\">E-mail</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($usersOfGroup !== FALSE){

				    foreach($usersOfGroup as $user){

				    	echo "<tr>";

					    	echo "<td>";
					    		echo $user['id'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $user['name'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $user['cpf'];
					    	echo "</td>";

					    	echo "<td>";
					    	 	echo $user['email'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo anchor("usuario/removeUserFromGroup/{$user['id']}/{$idGroup}", "<i class='fa fa-eraser'></i> Remover Usuário", "class='btn btn-danger'");
					    	echo "</td>";

				    	echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=5>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Não há usuários cadastrados nesse grupo no momento.</h4>";
	                            echo "</div>";
					    	echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}