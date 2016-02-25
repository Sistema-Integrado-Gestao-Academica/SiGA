<?php

require_once(APPPATH."/controllers/schedule.php");
require_once(APPPATH."/controllers/request.php");
require_once(APPPATH."/controllers/course.php");
require_once(APPPATH."/controllers/program.php");
require_once(APPPATH."/controllers/offer.php");
require_once(APPPATH."/controllers/discipline.php");
require_once(APPPATH."/controllers/syllabus.php");
require_once(APPPATH."/controllers/usuario.php");
require_once(APPPATH."/controllers/module.php");
require_once(APPPATH."/controllers/mastermind.php");
require_once(APPPATH."/controllers/coordinator.php");

require_once(APPPATH."/constants/EnrollmentConstants.php");
require_once(APPPATH."/constants/GroupConstants.php");

/**
 * Builds the table declaration html code with the standard css class
 * @param $tableId - The html ID for the table
 */
function buildTableDeclaration($tableId = FALSE){

	if($tableId !== FALSE){
		echo "<div id='".$tableId."' class=\"box-body table-responsive no-padding\">";
	}else{
		echo "<div class=\"box-body table-responsive no-padding\">";
	}
		echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
}

/**
 * Builds the table headers html code
 * @param $headersName - The headers names of the table
 */
function buildTableHeaders($headersNames){

	echo "<tr>";
	foreach($headersNames as $headerName){
		echo "<th class=\"text-center\">".$headerName."</th>";
	}
	echo "</tr>";
}

/**
 * Builds the table end declaration html code
 */
function buildTableEndDeclaration(){
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
}

function displayStudentSpecificDataPage($idUser){

	$user = new Usuario();

	$studentData = $user->getStudentBasicInformation($idUser);
	echo "<h4>Dados Pessoais:</h4>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Matrícula',
		'E-mail',
		'Telefone (Residência)',
		'Telefone (Celular)'
	));

	if($studentData !== FALSE){
		echo "<tr>";
			echo "<td>";
				echo $studentData['student_registration'];
			echo "</td>";
			echo "<td>";
				echo $studentData['email'];
			echo "</td>";
			echo "<td>";
				echo $studentData['home_phone_number'];
			echo "</td>";
			echo "<td>";
				echo $studentData['cell_phone_number'];
			echo "</td>";
		echo "</tr>";
	}else{

		echo "<tr>";
		echo "<td colspan=5>";
		echo "<div class=\"callout callout-warning\">";
		echo "<h4>Você ainda não atualizou seus dados.</h4>";
		echo "</div>";
		echo "</td>";
		echo "</tr>";
	}

	buildTableEndDeclaration();
}

function courseTableToSecretaryPage($courses){

	$courseController = new Course();

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Curso',
		'Tipo',
		'Ações'
	));

	if($courses !== FALSE){
    	foreach($courses as $courseData){

    		$courseId = $courseData['id_course'];
    		$courseType = $courseController->getCourseTypeByCourseId($courseId);

			echo "<tr>";
	    		echo "<td>";
	    		echo $courseId;
	    		echo "</td>";

	    		echo "<td>";
	    		echo $courseData['course_name'];
	    		echo "</td>";

	    		echo "<td>";
	    		echo $courseType['description'];
	    		echo "</td>";

	    		echo "<td>";
	    		echo anchor("enrollStudent/{$courseId}","<i class='fa fa-plus-square'>Matricular Aluno</i>", "class='btn btn-primary'");
	    		echo "</td>";
    		echo "</tr>";
    	}
    }

    buildTableEndDeclaration();
}

function showCapesAvaliationsNews($atualizations){
	$courseController = new Course();

	if ($atualizations){
		echo "<div class='panel panel-primary'>";

			echo "<div class='panel-heading'><h4> Ultimas atualizações de avaliações <i>CAPES</i> </h4></div>";

			foreach ($atualizations as $new => $courseAtualization){

				$course = $courseController->getCourseById($courseAtualization['id_course']);

				echo "<div class='panel-body'>";
					echo "<div class='modal-info'>";
						echo "<div class='modal-content'>";
							echo "<div class='modal-header bg-news'";
								echo "<h4 class='model-title'>". $course['course_name'] ."</h4>";
							echo "</div>";
							echo "<div class='modal-body'>";
								echo "<h3>";
									echo "<label class='label label-info'>";
										echo "Nota Obtida: ". $courseAtualization['course_grade']. "";
									echo "</label>";
									echo "               ".anchor("capesavaliation/checkAsVisualized/{$courseAtualization['id_avaliation']}", "<span class='fa fa-check'></span>", "class='btn btn-success'");
								echo "</h3>";

							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
			echo "<div class='panel-footer' align='center'><i>Clique em <span class='fa fa-check'></span> para marcar como vizualizada</i></div>";

		echo "</div>";
	}else{
		echo "<div class='panel panel-primary'>";

		echo "<div class='panel-heading'><h4> Ultimas atualizações de avaliações <i>CAPES</i> </h4></div>";
			echo "<h3>";
				echo "<label class='label label-info'>";
					echo "Não existem atualizações até o momento.";
				echo "</label>";
			echo "</h3>";
		echo "</div>";
	}
}

function studentsReportsTable($idCoordinator){
	$coordinator = new Coordinator();

	$totalStudent = $coordinator->getTotalStudents($idCoordinator);
	$enroledStudents = $coordinator->getEnroledStudents($idCoordinator);
	$notEnroledStudents = $coordinator->getNotEnroledStudents($idCoordinator);
	echo "<h4> Painel de quantidades de alunos </h4>";
	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
				echo "<tr>";
					echo "<th class=\"text-center\">Total de estudantes</th>";
					echo "<th class=\"text-center\">Total de Matriculados</th>";
					echo "<th class=\"text-center\">Total de Atrazados</th>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>";
					echo $totalStudent;
					echo "</td>";
					echo "<td>";
					echo $enroledStudents;
					echo "</td>";
					echo "<td>";
					echo $notEnroledStudents;
					echo "</td>";
				echo "</tr>";

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function secretaryReportsTable($idCoordinator){
	$coordinator = new Coordinator();

	$course = $coordinator->getCoordinatorCourseData($idCoordinator);
	$secretaries = $coordinator->getCourseSecretaries($course['id_course']);

	echo "<div class=\"col-lg-12 col-xs-6\">";
	echo "<div class='panel panel-primary'>";
	echo "<div class='panel-heading'><h4>Relação de secretários do curso: ". $course['course_name'] ." </h4></div>";
	echo "<div class='panel-body'>";
	echo "<div class=\"modal-info\">";
	echo "<div class=\"modal-content\">";

	foreach ($secretaries as $key => $secretary){
		$userData = new Usuario();
		$secretaryData = $userData->getUserById($secretary['id_user']);
		$secretaryGroup = $userData->getUserGroupNameByIdGroup($secretary['id_group']);
		echo "<div class=\"modal-header bg-news\">";
			echo "<h4 class=\"model-title\"> Secretário : ". ucfirst($secretaryData['name']) ."</h4>";
		echo "</div>";
		echo "<div class=\"modal-body\">";
			echo "<h4>";
				switch ($secretaryGroup) {
					case GroupConstants::ACADEMIC_SECRETARY_GROUP:
						echo "Secretaria acadêmica";
						break;
					case GroupConstants::FINANCIAL_SECRETARY_GROUP:
						echo "Secretaria financeira";
						break;
					default:
						break;
				}
			echo "</h4>";
		echo "</div>";

	}

					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";

}

function mastermindReportsTable($idCoordinator){
	$coordinator = new Coordinator();

	$totalMasterminds = $coordinator->getTotalMasterminds($idCoordinator);

	echo "<div class=\"col-lg-12 col-xs-6\">";
		echo "<div class=\"modal-info\">";
			echo "<div class=\"modal-content\">";
				echo "<div class=\"modal-header bg-news\">";
					echo "<h4 class=\"model-title\">Total de Professores do Curso: </h4>";
				echo "</div>";
				echo "<div class=\"modal-body\">";
					echo "<h4>";
						echo "Existem no momento " . sizeof($totalMasterminds) . " professores cadastrados para este curso.";
					echo "</h4>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	showMastermindsStudents($totalMasterminds);

}

function showMastermindsStudents($masterminds){

	$coordinator = new Coordinator();

	echo "<div class=\"col-lg-12 col-xs-6\">";
		echo "<div class='panel panel-primary'>";
			echo "<div class='panel-heading'><h4>Relação de Alunos por Professores: </h4></div>";
			echo "<div class='panel-body'>";
				echo "<div class=\"modal-info\">";
					echo "<div class=\"modal-content\">";
	foreach ($masterminds as $key => $mastermind){
		$students = $coordinator->getMastermindStudents($mastermind['id_user']);

		$userData = new Usuario();
		$mastermindData = $userData->getUserById($mastermind['id_user']);

						echo "<div class=\"modal-header bg-news\">";
							echo "<h4 class=\"model-title\"> Professor : ". ucfirst($mastermindData['name']) ."</h4>";
						echo "</div>";
		foreach ($students as $singleStudent){
			$studentData = $userData->getUserById($singleStudent['id_student']);

						echo "<div class=\"modal-body\">";
							echo "<h4>";
								echo ucfirst($studentData['name']);
							echo "</h4>";
						echo "</div>";

		}

	}

					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";


}

function courseTableToSecretaryCheckMastermind($courses){
$courseController = new Course();

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
				echo "<tr>";
					echo "<th class=\"text-center\">Código</th>";
					echo "<th class=\"text-center\">Curso</th>";
					echo "<th class=\"text-center\">Tipo</th>";
					echo "<th class=\"text-center\">Ações</th>";
				echo "</tr>";

				foreach($courses as $courseData){

					$courseId = $courseData['id_course'];
					$courseType = $courseController->getCourseTypeByCourseId($courseId);

					echo "<tr>";
						echo "<td>";
						echo $courseId;
						echo "</td>";

						echo "<td>";
						echo $courseData['course_name'];
						echo "</td>";

						echo "<td>";
						echo $courseType['description'];
						echo "</td>";

						echo "<td>";
						echo anchor("checkMastermind/{$courseId}","<i class='fa fa-plus-square'>Checar Orientadores do Curso</i>", "class='btn btn-primary'");
						echo "</td>";
					echo "</tr>";
				}

			echo "</tbody>";
		echo "</table>";
	echo "</div>";

}

function showExistingMastermindStudentsRelations($relationsToTable, $courseId){

	echo anchor("enrollMastermind/{$courseId}","<i class='fa fa-plus-circle'></i> Cadastrar Orientador", "class='btn-lg'");
	echo "<br>";
	echo "<br>";

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

				if ($relationsToTable !== FALSE){
					echo "<tr>";
					echo "<th class=\"text-center\">Orientador</th>";
					echo "<th class=\"text-center\">Estudante</th>";
					echo "<th class=\"text-center\">Ações</th>";
					echo "</tr>";

						foreach ($relationsToTable as $mastermindAndStudent){
							echo "<tr>";
								echo "<td>";
								echo $mastermindAndStudent['mastermind_name'];
								echo "</td>";

								echo "<td>";
								echo $mastermindAndStudent['student_name'];
								echo "</td>";

								echo "<td>";
								echo anchor("mastermind/deleteMastermindStudentRelation/{$mastermindAndStudent['mastermind_id']}/{$mastermindAndStudent['student_id']}/{$courseId}","<i class='glyphicon glyphicon-remove'></i>", "class='btn btn-danger'");
								echo anchor("mastermind/titlingAreaUpdateBySecretary/{$mastermindAndStudent['mastermind_id']}","<i class='fa fa-pencil'>Editar area de titulação</i>", "class='btn btn-default'");
								echo "</td>";
							echo "</tr>";
						}
				}else{
					echo "<div class='callout callout-info'>";
					echo "<h4>Não existem orientadores designados no momento.</h4>";
					echo "</div>";
				}
			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function secretaryCoursesToRequestReport($courses){

	$courseController = new Course();

	echo "<div class=\"box-body table-responsive no-padding\">";
	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
		    echo "<tr>";
		        echo "<th class=\"text-center\">Código</th>";
		        echo "<th class=\"text-center\">Curso</th>";
		        echo "<th class=\"text-center\">Tipo</th>";
		        echo "<th class=\"text-center\">Ações</th>";
		    echo "</tr>";

		    	foreach($courses as $courseData){

		    		$courseId = $courseData['id_course'];
		    		$courseType = $courseController->getCourseTypeByCourseId($courseId);

					echo "<tr>";
			    		echo "<td>";
			    		echo $courseId;
			    		echo "</td>";

			    		echo "<td>";
			    		echo $courseData['course_name'];
			    		echo "</td>";

			    		echo "<td>";
			    		echo $courseType['description'];
			    		echo "</td>";

			    		echo "<td>";
			    		echo anchor("request/courseRequests/{$courseId}","<i class='fa fa-plus-square'>Visualizar Solicitações</i>", "class='btn btn-primary'");
			    		echo "</td>";
		    		echo "</tr>";
		    	}

		echo "</tbody>";
	echo "</table>";
	echo "</div>";

}

function switchRequestGeneralStatus($requestStatus){

	switch($requestStatus){
		case EnrollmentConstants::REQUEST_INCOMPLETE_STATUS:
			$status = "<h4><span class='label label-warning'>Incompleta</span></h4>";
			break;

		case EnrollmentConstants::REQUEST_ALL_APPROVED_STATUS:
			$status = "<h4><span class='label label-success'>Aprovada</span></h4>";
			break;

		case EnrollmentConstants::REQUEST_ALL_REFUSED_STATUS:
			$status = "<h4><span class='label label-danger'>Recusada</span></h4>";
			break;

		case EnrollmentConstants::REQUEST_PARTIALLY_APPROVED_STATUS:
			$status = "<h4><span class='label label-info'>Parcialmente aprovada</span></h4>";
			break;

		case EnrollmentConstants::ENROLLED_STATUS:
			$status = "<h4><span class='label label-success'>Matriculado</span></h4>";
			break;

		default:
			$status = "-";
			break;
	}

	return $status;
}

function displayCourseRequests($requests, $courseId){

	echo "<div class='row'>";
		echo "<div class='col-md-6'>";
		searchForStudentRequestByIdForm($courseId);
		echo "</div>";

		echo "<div class='col-md-6'>";
		searchForStudentRequestByNameForm($courseId);
		echo "</div>";
	echo "</div>";

	echo "<br>";
	echo "<h3>Solicitações:</h3>";
	echo "<br>";

	echo anchor("request/courseRequests/{$courseId}", "Visualizar todas", "class='btn bg-olive btn-flat' style='margin-bottom:1%;'");

	$user = new Usuario();

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Código da requisição</th>";
			        echo "<th class=\"text-center\">Aluno requerente</th>";
			        echo "<th class=\"text-center\">Matrícula aluno</th>";
			        echo "<th class=\"text-center\">Status da solicitação</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($requests !== FALSE){

			    	foreach($requests as $request){

			    		echo "<tr>";

			    		echo "<td>";
			    		echo $request['id_request'];
			    		echo "</td>";

			    		$foundUser = $user->getUserById($request['id_student']);
			    		echo "<td>";
			    		echo $foundUser['name'];
			    		echo "</td>";

			    		echo "<td>";
			    		echo $foundUser['id'];
			    		echo "</td>";

			    		echo "<td>";
			    			$status = switchRequestGeneralStatus($request['request_status']);
			    			echo $status;
			    		echo "</td>";

			    		echo "<td>";
			    		echo anchor(
			    				"#solicitation_details_".$request['id_request'],
			    				"Visualizar solicitação",
			    				"class='btn btn-info'
			    				data-toggle='collapse'
			    				aria-expanded='false'
			    				aria-controls='solicitation_details".$request['id_request']."'"
			    			);

			    		$requestIsApprovedByMastermind = $request['mastermind_approval'] == EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;
			    		$requestIsNotFinalizedBySecretary = $request['secretary_approval'] != EnrollmentConstants::REQUEST_APPROVED_BY_SECRETARY;

			    		if($requestIsApprovedByMastermind){

			    			if($requestIsNotFinalizedBySecretary){

					    		if($request['request_status'] === EnrollmentConstants::REQUEST_ALL_APPROVED_STATUS){
					    			// In this case all request is already approved
					    		}else{
					    			echo "<br>";
					    			echo anchor("request/approveAllRequest/{$request['id_request']}/{$courseId}", "Aprovar toda solicitação", "class='btn btn-success' style='margin-top:5%;'");
					    		}

					    		echo "<br>";

					    		if($request['request_status'] === EnrollmentConstants::REQUEST_ALL_REFUSED_STATUS){
					    			// In this case all request is already refused
					    		}else{
					    			echo "<br>";
					    			echo anchor("request/refuseAllRequest/{$request['id_request']}/{$courseId}", "Recusar toda solicitação", "class='btn btn-danger'");
					    		}

					    		echo "<br>";
					    		echo "<div class=\"callout callout-info\">";
					    			echo anchor("request/finalizeRequestSecretary/{$request['id_request']}/{$courseId}", "Finalizar solicitação", "class='btn btn-primary btn-flat' style='margin-top: 5%;'");
									echo "<p><i>Finaliza a solicitação com o estado atual das disciplinas.</i></p>";
								echo "</div>";
			    			}else{
			    				echo "<div class=\"callout callout-info\">";
								echo "<h4>Solicitação já finalizada.</h4>";
								echo "<p>Essa solicitação já foi aprovada pela secretária.</p>";
								echo "</div>";
			    			}

			    		}else{

			    			echo "<div class=\"callout callout-info\">";
							echo "<h4>Solicitação não aprovada pelo orientador.</h4>";
							echo "<p>Apenas as solicitações já aprovadas pelo orientador podem ser editadas.</p>";
							echo "</div>";
			    		}

			    		echo "</td>";

			    		echo "</tr>";

			    		echo "<tr>";

			    		echo "<td colspan=4>";
				    		echo "<div class='collapse' id='solicitation_details_".$request['id_request']."'>";
							requestedDisciplineClasses($request['id_request'], EnrollmentConstants::REQUESTING_AREA_SECRETARY);
				    		echo "</div>";
			    		echo "</td>";

			    		echo "</tr>";
			    	}
			    }else{
					echo "<tr>";
			    	echo "<td colspan=5>";
						echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhuma solicitação encontrada.</h4>";
						echo "</div>";
	    			echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function requestedDisciplineClasses($requestId, $requestingArea){

	$requestController = new Request();

	$request = $requestController->getRequestById($requestId);
	$requestDisciplines = $requestController->getRequestDisciplinesClasses($requestId);
	$courseId = $requestController->getCourseIdByIdRequest($requestId);

	$discipline = new Discipline();

	echo "<div class='panel panel-info'>";

		echo "<div class='panel-heading'>Disciplinas solicitadas</div>";

		echo "<table class='table table-hover'>";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Código Disciplina</th>";
			        echo "<th class=\"text-center\">Disciplina requerida</th>";
			        echo "<th class=\"text-center\">Turma requerida</th>";
			        echo "<th class=\"text-center\">Vagas totais</th>";
			        echo "<th class=\"text-center\">Vagas disponíveis</th>";
			        echo "<th class=\"text-center\">Status</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($requestDisciplines !== FALSE){

		    		foreach($requestDisciplines as $disciplineClass){

						$foundDiscipline = $discipline->getDisciplineByCode($disciplineClass['id_discipline']);

						echo "<tr>";

							echo "<td>";
							echo $disciplineClass['id_discipline'];
							echo "</td>";

							if($foundDiscipline !== FALSE){
								echo "<td>";
								echo $foundDiscipline['discipline_name']." - ".$foundDiscipline['name_abbreviation'];
								echo "</td>";
							}else{
								echo "<td>";
									echo "<div class='callout callout-info'>";
									echo "Disciplina não encontrada.";
									echo "</div>";
								echo "</td>";
							}

							echo "<td>";
							echo $disciplineClass['class'];
							echo "</td>";

							echo "<td>";
							echo $disciplineClass['total_vacancies'];
							echo "</td>";

							echo "<td>";
							echo $disciplineClass['current_vacancies'];
							echo "</td>";

							echo "<td>";
								$status = switchRequestDisciplineStatus($disciplineClass['status']);
								echo $status;
							echo "</td>";

							echo "<td>";

							$requestIsNotFinalizedBySecretary = $request['secretary_approval'] != EnrollmentConstants::REQUEST_APPROVED_BY_SECRETARY;

							if($requestIsNotFinalizedBySecretary){

								$requestIsApprovedByMastermind = $request['mastermind_approval'] == EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;

								// Depends of the area that are treating the request
								switch($requestingArea){

									case EnrollmentConstants::REQUESTING_AREA_SECRETARY:

										if($requestIsApprovedByMastermind){

											if($disciplineClass['status'] === EnrollmentConstants::APPROVED_STATUS){
												// In this case the request was already approved
											}else{
												if($disciplineClass['mastermind_approval'] == EnrollmentConstants::DISCIPLINE_APPROVED_BY_MASTERMIND){
													echo anchor("request/approveRequestedDisciplineSecretary/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
												}else{
													echo "<div class=\"callout callout-danger\">";
													echo "<h6>Recusado pelo orientador. Sem ações.</h6>";
													echo "</div>";
												}
											}

											if($disciplineClass['status'] === EnrollmentConstants::REFUSED_STATUS){
												// In this case the request was already refused
											}else{
												echo anchor("request/refuseRequestedDisciplineSecretary/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Recusar", "class='btn btn-danger btn-flat'");
											}
										}else{
											echo "<div class=\"callout callout-info\">";
											echo "<h6>Não aprovado pelo orientador. Sem ações.</h6>";
											echo "</div>";
										}

										break;

									case EnrollmentConstants::REQUESTING_AREA_MASTERMIND:

										if($requestIsApprovedByMastermind){

											echo "<div class=\"callout callout-warning\">";
											echo "<h6>Solicitação finalizada. Sem ações.</h6>";
											echo "</div>";
										}else{
											if($disciplineClass['status'] === EnrollmentConstants::APPROVED_STATUS){
												// In this case the request was already approved
											}else{
												echo anchor("request/approveRequestedDisciplineMastermind/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
											}

											if($disciplineClass['status'] === EnrollmentConstants::REFUSED_STATUS){
												// In this case the request was already refused
											}else{
												echo anchor("request/refuseRequestedDisciplineMastermind/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Recusar", "class='btn btn-danger btn-flat'");
											}
										}
										break;

									default:
										echo "Sem ações.";
										break;
								}
							}else{
								echo "<div class=\"callout callout-info\">";
								echo "<h6>Solicitação já finalizada. Sem ações.</h6>";
								echo "</div>";
							}

							echo "</td>";
						echo "</tr>";
					}
				}else{
					echo "<td colspan=7>";
						echo "<div class=\"callout callout-warning\">";
						echo "<h4>Não foram encontradas disciplinas para essa solicitação</h4>";
						echo "</div>";
					echo "</td>";
				}

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function requestedDisciplineClassesForMastermind($requestId, $idMastermind, $idStudent){

	$requestController = new Request();
	$requestDisciplines = $requestController->getRequestDisciplinesClasses($requestId);
	$courseId = $requestController->getCourseIdByIdRequest($requestId);
	$discipline = new Discipline();

	echo "<div class='panel panel-info'>";

	echo "<div class='panel-heading'>Disciplinas solicitadas</div>";

	echo "<table class='table table-hover'>";
	echo "<tbody>";
	echo "<tr>";
	echo "<th class=\"text-center\">Código Disciplina</th>";
	echo "<th class=\"text-center\">Disciplina requerida</th>";
	echo "<th class=\"text-center\">Turma requerida</th>";
	echo "<th class=\"text-center\">Vagas totais</th>";
	echo "<th class=\"text-center\">Vagas disponíveis</th>";
	echo "<th class=\"text-center\">Status</th>";
	echo "<th class=\"text-center\">Ações</th>";
	echo "</tr>";

	foreach($requestDisciplines as $disciplineClass){

		$foundDiscipline = $discipline->getDisciplineByCode($disciplineClass['id_discipline']);

		echo "<tr>";

		echo "<td>";
		echo $disciplineClass['id_discipline'];
		echo "</td>";

		if($foundDiscipline !== FALSE){
			echo "<td>";
			echo $foundDiscipline['discipline_name']." - ".$foundDiscipline['name_abbreviation'];
			echo "</td>";
		}else{
			echo "<td>";
			echo "<div class='callout callout-info'>";
			echo "Disciplina não encontrada.";
			echo "</div>";
			echo "</td>";
		}

		echo "<td>";
		echo $disciplineClass['class'];
		echo "</td>";

		echo "<td>";
		echo $disciplineClass['total_vacancies'];
		echo "</td>";

		echo "<td>";
		echo $disciplineClass['current_vacancies'];
		echo "</td>";

		echo "<td>";

		switch($disciplineClass['status']){
			case EnrollmentConstants::PRE_ENROLLED_STATUS:
				$status = "<h4><span class='label label-warning'>Pré-matriculado</span></h4>";
				break;

			case EnrollmentConstants::ENROLLED_STATUS:
				$status = "<h4><span class='label label-success'>Matriculado</span></h4>";
				break;

			case EnrollmentConstants::REFUSED_STATUS:
				$status = "<h4><span class='label label-danger'>Recusado</span></h4>";
				break;

			default:
				$status = "-";
				break;
		}
		echo $status;
		echo "</td>";

		echo "<td>";

		if($disciplineClass['status'] === EnrollmentConstants::ENROLLED_STATUS){
			// In this case the request was already approved
		}else{
			displayAcceptStudentDisciplineSolicitation($requestId, $disciplineClass['id_offer_discipline'], $courseId, $idMastermind, $idStudent);
			//echo anchor("request/approveRequestedDiscipline/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
		}

		if($disciplineClass['status'] === EnrollmentConstants::REFUSED_STATUS){
			// In this case the request was already refused
		}else{
			displayRefuseStudentDisciplineSolicitation($requestId, $disciplineClass['id_offer_discipline'], $courseId, $idMastermind, $idStudent);
			//echo anchor("request/refuseRequestedDiscipline/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Recusar", "class='btn btn-danger btn-flat'");
		}

		echo "</td>";
		echo "</tr>";
	}

	echo "</tbody>";
	echo "</table>";
	echo "</div>";
}


function displayMastermindStudentRequest($requests, $idMastermind){

	$user = new Usuario();
	$user->loadModel();

	echo "<br>";
	echo "<h3>Solicitações dos alunos orientados:</h3>";
	echo "<br>";

	echo "<div class=\"table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
				echo "<tr>";
					echo "<th class=\"text-center\">Código da requisição</th>";
					echo "<th class=\"text-center\">Aluno requerente</th>";
					echo "<th class=\"text-center\">Matrícula aluno</th>";
					echo "<th class=\"text-center\">Status da solicitação</th>";
					echo "<th class=\"text-center\">Ações</th>";
					echo "<th class=\"text-center\">Finalizar</th>";
				echo "</tr>";

				if($requests !== FALSE){

					$offer = new Offer();

					foreach($requests as $request){

						if ($request !== FALSE){

							foreach ($request as $studentRequest){

								$requestId = $studentRequest['id_request'];

								$semesterId = $studentRequest['id_semester'];
								$courseId = $studentRequest['id_course'];
								$requestedOffer = $offer->getOfferBySemesterAndCourse($semesterId, $courseId);

								if($requestedOffer !== FALSE){
									$needsMastermindApproval = $requestedOffer['needs_mastermind_approval'] == EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL;
								}else{
									// Assume that is true
									$needsMastermindApproval = TRUE;
								}

								$requestIsApprovedByMastermind = $studentRequest['mastermind_approval'] == EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;

								echo "<tr>";

								echo "<td>";
								echo $requestId;
								echo "</td>";

								$foundUser = $user->getUserById($studentRequest['id_student']);
								echo "<td>";
								echo $foundUser['name'];
								echo "</td>";

								echo "<td>";
								echo $foundUser['id'];
								echo "</td>";

								echo "<td>";

								$status = switchRequestGeneralStatus($studentRequest['request_status']);

								if($requestIsApprovedByMastermind){
									if($needsMastermindApproval){
										$status = $status."<h4><span class='label label-primary'>Finalizada pelo orientador</span></h4>";
									}else{
										$status = $status."<h4><span class='label label-warning'>Oferta não permite ação do orientador</span></h4>";
									}
									echo $status;
								}else{
									echo $status;
								}

								echo "</td>";

								echo "<td>";

								echo anchor(
										"#solicitation_details_".$requestId,
										"Visualizar solicitação",
										"class='btn btn-info'
					    				data-toggle='collapse'
					    				aria-expanded='false'
					    				aria-controls='solicitation_details".$requestId."'"
									);

								if($requestIsApprovedByMastermind){

									// Disable buttons
									echo anchor("", "Aprovar toda solicitação", "class='btn btn-success' style='margin-top:5%;' disabled='true'");
									echo "<br>";
									echo anchor("", "Recusar toda solicitação", "class='btn btn-danger' style='margin-top:5%;' disabled='true'");
								}else{
									echo "<br>";
									echo anchor("request/approveAllStudentRequestsByMastermind/{$requestId}/{$studentRequest['id_student']}", "Aprovar toda solicitação", "class='btn btn-success' style='margin-top:5%;'");
									echo "<br>";
									echo anchor("request/refuseAllStudentRequestsByMastermind/{$requestId}/{$studentRequest['id_student']}", "Recusar toda solicitação", "class='btn btn-danger' style='margin-top:5%;'");
								}

								echo "</td>";

								echo "<td rowspan=2>";
									if($requestIsApprovedByMastermind){

										if($needsMastermindApproval){

											$mastermind = new MasterMind();

											$message = $mastermind->getMastermindMessage($idMastermind, $requestId);

											$isFinalized = TRUE;
											echo "<div class=\"callout callout-warning\">";
												mastermindMessageForm($requestId, $idMastermind, $isFinalized, $message);
												echo "<p><i>Solicitação finalizada. É possível alterar a mensagem deixada para o aluno.</i></p>";
											echo "</div>";
										}else{
											echo "<div class=\"callout callout-warning\">";
												echo "<p><i>O tipo da oferta não permite a ação do orientador.</i></p>";
											echo "</div>";
										}

									}else{
										$isFinalized = FALSE;
										echo "<div class=\"callout callout-info\">";
											mastermindMessageForm($requestId, $idMastermind, $isFinalized);
											echo "<p><i>Finaliza a solicitação com o status atual das disciplinas.</i></p>";
										echo "</div>";
									}
								echo "</td>";

								echo "</tr>";

								echo "<tr>";
									echo "<td colspan=5>";
										echo "<div class='collapse' id='solicitation_details_".$requestId."'>";
										requestedDisciplineClasses($requestId, EnrollmentConstants::REQUESTING_AREA_MASTERMIND);
										echo "</div>";
									echo "</td>";
								echo "</tr>";
							}
						}
					}
				}else{
					echo "<tr>";
						echo "<td colspan=9>";
						echo "<div class=\"callout callout-info\">";
						echo "<h4>Nenhuma solicitação encontrada.</h4>";
						echo "</div>";
						echo "</td>";
					echo "</tr>";
				}

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displaySentDisciplinesToEnrollmentRequest($requestDisciplinesClasses){

	$discipline = new Discipline();

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Código</th>";
			        echo "<th class=\"text-center\">Disciplina</th>";
			        echo "<th class=\"text-center\">Turma</th>";
			        echo "<th class=\"text-center\">OBS</th>";
			    echo "</tr>";

			    if($requestDisciplinesClasses !== FALSE){

			    	foreach($requestDisciplinesClasses as $class){

		    			$foundDiscipline = $discipline->getDisciplineByCode($class['id_discipline']);

		    			$disciplineRequestStatus = switchRequestDisciplineStatus($class['status']);

						echo "<tr>";
				    		echo "<td>";
				    		echo $class['id_offer_discipline'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo "Cod.: ".$foundDiscipline['discipline_code']." - ".$foundDiscipline['discipline_name']." (".$foundDiscipline['name_abbreviation'].")";
				    		echo "</td>";

				    		echo "<td>";
				    		echo $class['class'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $disciplineRequestStatus;
				    		echo "</td>";

			    		echo "</tr>";

			    	}
			    }else{
					echo "<tr>";
			    	echo "<td colspan=4>";
						echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhuma disciplina adicionada para solicitação de matrícula.</h4>";
						echo "</div>";
	    			echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function switchRequestDisciplineStatus($status){

	switch($status){
		case EnrollmentConstants::PRE_ENROLLED_STATUS:
			$disciplineRequestStatus = "<h4><span class='label label-info'>Pré-matriculado</span></h4>";
			break;

		case EnrollmentConstants::NO_VACANCY_STATUS:
			$disciplineRequestStatus = "<h4><span class='label label-warning'>Não matriculado. Não há vagas.</span></h4>";
			break;

		case EnrollmentConstants::APPROVED_STATUS:
			$disciplineRequestStatus = "<h4><span class='label label-success'>Disciplina aprovada</span></h4>";
			break;

		case EnrollmentConstants::REFUSED_STATUS:
			$disciplineRequestStatus = "<h4><span class='label label-danger'>Disciplina recusada</span></h4>";
			break;

		default:
			$disciplineRequestStatus = "-" ;
			break;
	}

	return $disciplineRequestStatus;
}

function displayDisciplinesToRequest($request, $courseId, $userId, $semesterId){

	$offer = new Offer();
	$offer->loadModel();

	$discipline = new Discipline();

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Código</th>";
			        echo "<th class=\"text-center\">Disciplina</th>";
			        echo "<th class=\"text-center\">Turma</th>";
			        echo "<th class=\"text-center\">Horário</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($request != FALSE){

			    	foreach($request as $request){

			    		$foundClass = $offer->getOfferDisciplineById($request['discipline_class']);

			    		if($foundClass !== FALSE){

			    			$foundDiscipline = $discipline->getDisciplineByCode($foundClass['id_discipline']);
							echo "<tr>";
					    		echo "<td>";
					    		echo $foundClass['id_offer_discipline'];
					    		echo "</td>";

					    		echo "<td>";
					    		echo "Cod.: ".$foundDiscipline['discipline_code']." - ".$foundDiscipline['discipline_name']." (".$foundDiscipline['name_abbreviation'].")";
					    		echo "</td>";

					    		echo "<td>";
					    		echo $foundClass['class'];
					    		echo "</td>";

					    		echo "<td>";
					    		displayDisciplineHours($foundClass['id_offer_discipline']);
					    		echo "</td>";

					    		echo "<td>";
					    		echo anchor(
					    				"temporaryrequest/removeDisciplineFromTempRequest/{$userId}/{$courseId}/{$semesterId}/{$foundDiscipline['discipline_code']}/{$foundClass['class']}",
				    					"Remover Disciplina",
				    					"class='btn btn-danger btn-flat'"
					    			);
					    		echo "<td>";
				    		echo "</tr>";
			    		}else{
			    			echo "<tr>";
					    		echo "<td>";
					    		echo $foundClass['id_offer_discipline'];
					    		echo "</td>";

					    		echo "<td colspan='3'>";
					    		echo "<div class=\"callout callout-info\">";
									echo "<h4>Não foi encontrada a turma informada.</h4>";
								echo "</div>";
					    		echo "</td>";
				    		echo "</tr>";
			    		}
			    	}
			    }else{
					echo "<tr>";
			    	echo "<td colspan=4>";
						echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhuma disciplina adicionada para solicitação de matrícula.</h4>";
						echo "</div>";
	    			echo "</td>";
					echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayDisciplineClasses($disciplineClasses){

	$user = new Usuario();

   	if($disciplineClasses !== FALSE){

	   	foreach ($disciplineClasses as $class) {

	   		$mainTeacher = $user->getUserById($class['main_teacher']);

	   		echo "<div class='row' align='center'>";
			echo "<div class='panel panel-primary' style='width:40%;'>";
				echo "<div class='panel-heading'>";
		    	echo "<h4>"."Turma - <b>".$class['class']."</b><h4>";
				echo "</div>";

	   			echo "<div class='panel-body'>";

			    	echo "Vagas totais: <b>".$class['total_vacancies']."</b>";
			    	echo "<hr>";
			    	echo "Vagas disponíveis: <b>".$class['current_vacancies']."</b>";
			    	echo "<hr>";
			    	if($class['secondary_teacher'] !== NULL){
						$secondaryTeacher = $user->getUserById($class['secondary_teacher']);
						$secondaryTeacher = $secondaryTeacher['name'];
			    		echo "Professores: <b>".$mainTeacher['name']."</b> e <b>".$secondaryTeacher."</b>";
					}else{
			    		echo "Professores: <b>".$mainTeacher['name']."</b>";
					}
			    	echo "<hr>";
				echo "</div>";

		    	echo "<div class='panel-footer' align='left'>";
		    	displayDisciplineHours($class['id_offer_discipline']);
				echo "</div>";

			echo "</div>";
			echo "</div>";
	   	}
	}else{

		echo "<div class=\"callout callout-info\">";
			echo "<h4>Nenhuma turma cadastrada para oferta.</h4>";
		echo "</div>";
	}
}

function displayOfferListDisciplines($offerListDisciplines, $courseId){

	echo "<div class=\"box-body table-responsive no-padding\">";
	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
		    echo "<tr>";
		        echo "<th class=\"text-center\">Código</th>";
		        echo "<th class=\"text-center\">Disciplina</th>";
		        echo "<th class=\"text-center\">Créditos</th>";
		    echo "</tr>";

		    if($offerListDisciplines != FALSE){

		    	foreach($offerListDisciplines as $discipline){

					echo "<tr>";
			    		echo "<td>";
			    		echo $discipline['discipline_code'];
			    		echo "</td>";

			    		echo "<td>";
			    		echo anchor("discipline/displayDisciplineClassesToEnroll/{$courseId}/{$discipline['discipline_code']}", "<b>".$discipline['discipline_name']." - ".$discipline['name_abbreviation']."</b>");
			    		echo "</td>";

			    		echo "<td>";
			    		echo $discipline['credits'];
			    		echo "</td>";
		    		echo "</tr>";
		    	}
		    }else{
				echo "<tr>";
		    	echo "<td colspan=3>";
					echo "<div class=\"callout callout-info\">";
						echo "<h4>Nenhuma lista de oferta cadastrada para o semestre atual.</h4>";
					echo "</div>";
    			echo "</td>";
				echo "</tr>";
		    }

		echo "</tbody>";
	echo "</table>";
echo "</div>";

}

function displayOfferDisciplineClasses($idDiscipline, $idOffer, $offerDisciplineClasses, $teachers, $idCourse){

	if($offerDisciplineClasses !== FALSE){

		$user = new Usuario();

		foreach($offerDisciplineClasses as $class){

			$mainTeacher = $user->getUserById($class['main_teacher']);

			if($class['secondary_teacher'] !== NULL){
				$secondaryTeacher = $user->getUserById($class['secondary_teacher']);
				$secondaryTeacher = $secondaryTeacher['name'];
			}else{
				$secondaryTeacher = "-";
			}

			echo "<div class=\"box-body table-responsive no-padding\">";
			echo "<table class=\"table table-bordered table-hover\">";
				echo "<tbody>";
				    echo "<tr>";
				        echo "<th class=\"text-center\">Turma</th>";
				        echo "<th class=\"text-center\">Vagas totais</th>";
				        echo "<th class=\"text-center\">Vagas atuais</th>";
				        echo "<th class=\"text-center\">Professor principal</th>";
				        echo "<th class=\"text-center\">Professor secundário</th>";
				        echo "<th class=\"text-center\">Horários</th>";
				        echo "<th class=\"text-center\">Ações</th>";
				    echo "</tr>";

				    echo "<tr>";

				    	echo "<td>";
				    	echo $class['class'];
				    	echo "</td>";

				    	echo "<td>";
				    	echo $class['total_vacancies'];
				    	echo "</td>";

				    	echo "<td>";
				    	echo $class['current_vacancies'];
				    	echo "</td>";

				    	echo "<td>";
				    	echo $mainTeacher['name'];
				    	echo "</td>";

				    	echo "<td>";
				    	echo $secondaryTeacher;
				    	echo "</td>";

				    	echo "<td>";
						displayDisciplineHours($class['id_offer_discipline']);
				    	echo "</td>";

				    	echo "<td>";
		    			echo anchor("offer/formToUpdateDisciplineClass/{$idOffer}/{$idDiscipline}/{$class['class']}/{$idCourse}","Editar turma", "class='btn btn-warning' style='margin-right:5%; margin-bottom:10%;'");
		    			echo anchor("offer/deleteDisciplineClass/{$idOffer}/{$idDiscipline}/{$class['class']}/{$idCourse}","Remover turma", "class='btn btn-danger'");
				    	echo "</td>";

				    echo "</tr>";

				echo "</tbody>";
			echo "</table>";
			echo "</div>";
		}

		formToNewOfferDisciplineClass($idDiscipline, $idOffer, $teachers, $idCourse);

	}else{
		echo "<div class=\"callout callout-info\">";
			echo "<h4>Nenhuma turma cadastrada no momento.</h4>";
			echo "<p>Cadastre logo abaixo.</p>";
		echo "</div>";

		formToNewOfferDisciplineClass($idDiscipline, $idOffer, $teachers, $idCourse);
	}
}

function displayDisciplineHours($idOfferDiscipline){

	$schedule = new Schedule();
	$schedule->getDisciplineHours($idOfferDiscipline);
	$disciplineSchedule = $schedule->getDisciplineSchedule();

	if(sizeof($disciplineSchedule) > 0){

		echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			    echo "<tr>";
			        echo "<th class=\"text-center\">Dia-Horário</th>";
			        echo "<th class=\"text-center\">Local</th>";
			    echo "</tr>";
	    		foreach($disciplineSchedule as $classHour){
	    			echo "<tr>";

	    			$classHourData = $classHour->getClassHour();

	    			echo "<td>";
	    			echo "<b>".$classHour->getDayHourPair()."</b>";
	    			echo "</td>";

	    			echo "<td>";
	    			if($classHourData['local'] !== NULL){
	    				echo $classHourData['local'];
	    			}else{
	    				echo "<i>Não definido</i>";
	    			}
	    			echo "</td>";

					echo "</tr>";
	    		}
			echo "</tbody>";
		echo "</table>";
		echo "</div>";
	}else{
		echo "<div class='callout callout-info'>";
		echo "<h4>Sem horários adicionados no momento.</h4>";
		echo "</div>";
	}
}

function drawFullScheduleTable($offerDiscipline, $idCourse){

	$schedule = new Schedule();

	$schedule->drawFullSchedule($offerDiscipline, $idCourse);
}

function formToUpdateOfferDisciplineClass($disciplineId, $idOffer, $teachers, $offerDisciplineClass, $idCourse){

	$disciplineClass = array(
		"name" => "disciplineClass",
		"id" => "disciplineClass",
		"type" => "text",
		"class" => "form-campo",
		"class" => "form-control",
		"maxlength" => "3",
		"value" => $offerDisciplineClass['class']
	);

	$totalVacancies = array(
		"name" => "totalVacancies",
		"id" => "totalVacancies",
		"type" => "number",
		"class" => "form-campo",
		"class" => "form-control",
		"min" => "0",
		"value" => $offerDisciplineClass['total_vacancies']
	);

	$submitBtn = array(
		"class" => "btn bg-olive btn-block",
		"type" => "submit",
		"content" => "Salvar alterações"
	);

	echo form_open("offer/updateOfferDisciplineClass/{$disciplineId}/{$idOffer}/{$offerDisciplineClass['class']}");

	echo form_hidden('course', $idCourse);

	echo "<div class='form-box'>";
	echo"<div class='header'>Editar turma para oferta</div>";
	echo "<div class='body bg-gray'>";

	echo "<div class='form-group'>";
	echo form_label("Turma", "disciplineClass");
	echo form_input($disciplineClass);
	echo form_error("disciplineClass");
	echo "</div>";

	echo "<div class='form-group'>";
	echo form_label("Vagas totais", "totalVacancies");
	echo form_input($totalVacancies);
	echo form_error("disciplineClass");
	echo "</div>";

	echo "<div class='form-group'>";
	echo form_label("Professor principal", "mainTeacher"). " ";
	if($teachers !== FALSE){
		echo form_dropdown("mainTeacher", $teachers, $offerDisciplineClass['main_teacher'], "class='form-control'");
	}else{
		$submitBtn['disabled'] = TRUE;
		echo form_dropdown("mainTeacher", array("Nenhum professor cadastrado."), '', "class='form-control'");
	}
	echo form_error("mainTeacher");
	echo "</div>";

	echo "<div class='form-group'>";
	echo form_label("Professor secundário", "secondaryTeacher"). " ";
	if($teachers !== FALSE){
		define("NONE_TEACHER", 0);
		$teachers[NONE_TEACHER] = "Nenhum";
		echo form_dropdown("secondaryTeacher", $teachers, $offerDisciplineClass['secondary_teacher'], "class='form-control'");
	}else{
		echo form_dropdown("secondaryTeacher", array("Nenhum professor cadastrado."), '', "class='form-control'");
	}
	echo form_error("secondaryTeacher");
	echo "</div>";

	echo "</div>";

	echo "<div class='footer bg-gray'>";
		echo "<div class='row'>";

		echo "<div class='col-lg-6'>";
		echo form_button($submitBtn);
		echo "</div>";

		echo "<div class='col-lg-6'>";
		echo anchor(
			"offer/displayDisciplineClasses/{$disciplineId}/{$idOffer}/{$idCourse}",
			"Voltar",
			"class='btn bg-olive btn-block'"
		);
		echo "</div>";

		echo "</div>";
	echo "</div>";

	echo "</div>";

	echo form_close();

	if($teachers === FALSE){
		echo "<div class='callout callout-danger'>";
			echo "<h4>Não é possível alterar uma turma para que fique sem um professor principal.</h4>";
			echo "<p>Contate o administrador.</p>";
		echo "</div>";
	}

	echo "<br>";
	echo "<br>";
	echo "<h3><i class='fa fa-clock-o'></i> Gerenciar horários da turma</h3>";
	echo "<br>";
	drawFullScheduleTable($offerDisciplineClass, $idCourse);
}

function displayRegisteredCoursesToProgram($programId, $courses){

	$program = new Program();

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código do curso</th>";
			        echo "<th class=\"text-center\">Curso</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($courses !== FALSE){

				    foreach($courses as $course){

				    	$courseIsOnProgram = $course['id_program'] == $programId;
				    	$courseIsOnNoneProgram = $course['id_program'] == NULL;

				    	echo "<tr>";

				    		echo "<td>";
				    			echo $course['id_course'];
				    		echo "</td>";

			    			echo "<td>";
			    				echo $course['course_name'];
			    			echo "</td>";

			    			echo "<td>";
			    				if($courseIsOnProgram){
		    						echo anchor("program/removeCourseFromProgram/{$course['id_course']}/{$programId}","<i class='fa fa-plus'></i> Remover do programa", "class='btn btn-danger'");
			    				}else if($courseIsOnNoneProgram){
		    						echo anchor("program/addCourseToProgram/{$course['id_course']}/{$programId}","<i class='fa fa-plus'></i> Adicionar ao programa", "class='btn btn-primary'");
			    				}else{
		    						echo anchor("","Sem ação.", "class='btn btn-primary' disabled='true'");
			    				}
			    			echo "</td>";

				    	echo "</tr>";
				    }
			    }else{
					echo "<td colspan=2>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhum curso cadastrado.</h4>";
						echo "</div>";
	    			echo "</td>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayRegisteredPrograms($programs){
	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\"><h3>Programas cadastrados</h3></th>";
			        echo "<th class=\"text-center\"><h3>Ações</h3></th>";
			    echo "</tr>";

			    if($programs !== FALSE){

			    	foreach($programs as $program){
			    		echo "<tr>";

			    			echo "<td>";
			    				echo $program['program_name']." - ".$program['acronym'];
			    			echo "</td>";

			    			echo "<td>";
			    				echo anchor("program/editProgram/{$program['id_program']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right: 5%' id='edit_program_btn' data-container=\"body\"
		             				data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		             				data-content=\"Aqui é possível editar os dados do programa e adicionar cursos a ele.\"");

			    				echo anchor("program/removeProgram/{$program['id_program']}", "<span class='glyphicon glyphicon-remove'></span>", "class='btn btn-danger' id='remove_program_btn' data-container=\"body\"
		             				data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		             				data-content=\"OBS.: Ao deletar um programa, todos os cursos associados a ele serão desassociados.\"");
			    			echo "</td>";

			    		echo "</tr>";
			    	}

			    }else{
			    	echo "<td colspan=2>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Não existem programas cadastrados</h4>";
					    	echo anchor("program/registerNewProgram", "Cadastrar Programa", "class='btn btn-primary'");
						echo "</div>";
	    			echo "</td>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayCoordinatorPrograms($programs){
	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\"><h3>Programas cadastrados</h3></th>";
			        echo "<th class=\"text-center\"><h3>Ações</h3></th>";
			    echo "</tr>";

			    if($programs !== FALSE){

			    	foreach($programs as $program){
			    		echo "<tr>";

			    			echo "<td>";
			    				echo $program['program_name']." - ".$program['acronym'];
			    			echo "</td>";

			    			echo "<td>";
			    			echo anchor("coordinator/displayProgramCourses/{$program['id_program']}", "Visualizar cursos", "class='btn btn-primary btn-flat'");
			    			echo "</td>";

			    		echo "</tr>";
			    	}

			    }else{
			    	echo "<td colspan=2>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Não existem programas cadastrados.</h4>";
						echo "</div>";
	    			echo "</td>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayProgramCourses($programId, $courses){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código do curso</th>";
			        echo "<th class=\"text-center\">Curso</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($courses !== FALSE){

				    foreach($courses as $course){

				    	echo "<tr>";

				    		echo "<td>";
				    			echo $course['id_course'];
				    		echo "</td>";

			    			echo "<td>";
			    				echo $course['course_name'];
			    			echo "</td>";

			    			echo "<td>";
		    				echo anchor("coordinator/displayCourseStudents/{$course['id_course']}", "Visualizar alunos do curso", "class='btn btn-primary btn-flat'");
			    			echo "</td>";

				    	echo "</tr>";
				    }
			    }else{
					echo "<td colspan=3>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhum curso cadastrado.</h4>";
						echo "</div>";
	    			echo "</td>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayCourseStudents($courseId, $students){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código do aluno</th>";
			        echo "<th class=\"text-center\">Aluno</th>";
			        echo "<th class=\"text-center\">E-mail</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($students !== FALSE){

				    foreach($students as $student){

				    	echo "<tr>";

				    		echo "<td>";
				    			echo $student['id'];
				    		echo "</td>";

			    			echo "<td>";
			    				echo $student['name'];
			    			echo "</td>";

			    			echo "<td>";
			    				echo $student['email'];
			    			echo "</td>";

			    			echo "<td>";

			    			echo "</td>";

				    	echo "</tr>";
				    }
			    }else{
					echo "<td colspan=4>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhum aluno matriculado neste curso.</h4>";
						echo "</div>";
	    			echo "</td>";
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

			    if($syllabus !== FALSE){

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
			    }else{
					echo "<td colspan=2>";
    					echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhum curso cadastrado.</h4>";
					    	echo anchor("syllabus/newSyllabus/{$courseId}", "Novo Currículo", "class='btn btn-primary'");
						echo "</div>";
	    			echo "</td>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displaySyllabusDisciplinesToStudent($syllabusDisciplines){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código da Disciplina</th>";
			        echo "<th class=\"text-center\">Disciplina</th>";
			        echo "<th class=\"text-center\">Créditos</th>";
			        echo "<th class=\"text-center\">Carga-horária</th>";
			    echo "</tr>";

			    if($syllabusDisciplines !== FALSE){

			    	foreach($syllabusDisciplines as $discipline){

				    	echo "<tr>";

					    	echo "<td>";
					    		echo $discipline['discipline_code'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $discipline['discipline_name']."  (".$discipline['name_abbreviation'].")";
					    	echo "</td>";

					    	echo "<td>";
					    		echo $discipline['credits'];
					    	echo "</td>";

					    	echo "<td>";
					    		echo $discipline['workload'];
					    	echo "</td>";

				    	echo "</tr>";
			    	}

			    }else{

			    	echo "<tr>";
			    		echo "<td colspan='4'>";
			    			echo "<div class=\"callout callout-info\">";
							echo "<h4>Nenhuma disciplina no currículo deste curso.</h4>";
						echo "</div>";
			    		echo "</td>";
			    	echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displaySyllabusDisciplines($syllabusId, $syllabusDisciplines, $courseId){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
			echo anchor("syllabus/addDisciplines/{$syllabusId}/{$courseId}", "Adicionar disciplinas", "class='btn btn-primary'");
			    echo "<tr>";
			        echo "<th class=\"text-center\">Disciplinas</th>";
			        echo "<th class=\"text-center\">Linhas de Pesquisa</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($syllabusDisciplines !== FALSE){

			    	foreach($syllabusDisciplines as $discipline){
			    		$disciplineController = new Discipline();
			    		$disciplineResearchLinesIds = $disciplineController->getDisciplineResearchLines($discipline['discipline_code']);

			    		$syllabus = new Syllabus();
			    		$disciplineResearchLinesNames = $syllabus->getDiscipineResearchLinesNames($disciplineResearchLinesIds);

				    	echo "<tr>";
					    	echo "<td>";
					    		echo $discipline['discipline_code']." - ".$discipline['discipline_name']." (".$discipline['name_abbreviation'].")";
					    	echo "</td>";
					    	echo "<td>";
					    	if ($disciplineResearchLinesNames){
					    		foreach ($disciplineResearchLinesNames as $names){
					    			echo $names."<br>";
					    		}
					    	}else{
					    		echo "Não relacionada a nenhuma linha de pesquisa.";
					    	}
					    	echo "</td>";
					    	echo "<td>";
					    	echo anchor("syllabus/relateDisciplineToResearchLine/{$discipline['discipline_code']}/{$syllabusId}/{$courseId}", "Relacionar Linha de Pesquisa", "class='btn btn-success'");
					    	echo "</td>";
				    	echo "</tr>";
			    	}
			    }else{

			    	echo "<tr>";
			    		echo "<td>";
			    			echo "<div class=\"callout callout-info\">";
								echo "<h4>Nenhuma disciplina adicionada ao currículo.</h4>";
							   	echo anchor("syllabus/addDisciplines/{$syllabusId}/{$courseId}", "Adicionar disciplinas", "class='btn btn-primary'");
							echo "</div>";
			    		echo "</td>";
			    	echo "</tr>";
			    }

			echo "</tbody>";
		echo "</table>";
	echo "</div>";
}

function displayDisciplinesToSyllabus($syllabusId, $allDisciplines, $courseId){

	echo "<div class=\"box-body table-responsive no-padding\">";
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";

			    echo "<tr>";
			        echo "<th class=\"text-center\">Código </th>";
			        echo "<th class=\"text-center\">Sigla</th>";
			        echo "<th class=\"text-center\">Disciplina</th>";
			        echo "<th class=\"text-center\">Créditos</th>";
			        echo "<th class=\"text-center\">Linhas de Pesquisa</th>";
			        echo "<th class=\"text-center\">Ações</th>";
			    echo "</tr>";

			    if($allDisciplines !== FALSE){

				    foreach($allDisciplines as $discipline){

					    $syllabus = new Syllabus();
			    		$disciplineAlreadyExistsInSyllabus = $syllabus->disciplineExistsInSyllabus($discipline['discipline_code'], $syllabusId);

			    		$disciplineController = new Discipline();
			    		$disciplineResearchLinesIds = $disciplineController->getDisciplineResearchLines($discipline['discipline_code']);
			    		if ($disciplineResearchLinesIds){
			    			$disciplineResearchLinesNames = $syllabus->getDiscipineResearchLinesNames($disciplineResearchLinesIds);
			    		}else{
			    			$disciplineResearchLinesNames = FALSE;
			    		}
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
					    		if ($disciplineResearchLinesNames){
					    			foreach ($disciplineResearchLinesNames as $names){
					    				echo $names."<br>";
					    			}
					    		}else{
					    			echo "Não relacionada a nenhuma linha de pesquisa.";
					    		}
					    	echo "</td>";

					    	echo "<td>";
					    		if($disciplineAlreadyExistsInSyllabus){
					    			echo anchor("syllabus/removeDisciplineFromSyllabus/{$syllabusId}/{$discipline['discipline_code']}/{$courseId}", "Remover disciplina", "class='btn btn-danger'");
					    		}else{
					    			echo anchor("syllabus/addDisciplineToSyllabus/{$syllabusId}/{$discipline['discipline_code']}/{$courseId}", "Adicionar disciplina", "class='btn btn-primary'");
					    		}
					    	echo "</td>";

					    echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
					    	echo "<td colspan=5>";
						    	echo "<div class=\"callout callout-warning\">";
	                            	echo "<h4>Nenhuma disciplina encontrada.</h4>";
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

			    			$newOfferBtn = array(
								"id" => "new_offer_btn",
								"class" => "btn btn-primary",
								"content" => "Nova Lista de Ofertas",
								"type" => "submit"
							);

			    			$needsMastermindApprovalCheckBox = array(
							    'name' => 'needs_mastermind_approval_ckbox',
							    'id' => 'needs_mastermind_approval_ckbox',
							    'value' => EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL,
							    'checked' => TRUE,
							    'style' => 'margin:15px',
						    );

			    			echo "<td colspan=3>";
		    					echo "<div class=\"callout callout-info\">";
									echo "<h4>Nenhuma lista de ofertas proposta para o semestre atual.</h4>";
	    						echo "<div class=\"callout callout-warning\">";
									echo form_open("offer/newOffer/{$courseId}");
									echo form_checkbox($needsMastermindApprovalCheckBox);
									echo form_label('Necessita de aprovação do orientador.', 'needs_mastermind_approval_ckbox');
									echo "<br>";
									echo form_button($newOfferBtn);
									echo form_close();
								echo "</div>";

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

	$offer = new Offer();
	$offerData = $offer->getOffer($idOffer);

	echo "<h2 class='principal'>Lista de Oferta</h2>";
	echo "<h3><b>Curso</b>: ".$course['course_name']."</h3>";

	if($offerData['needs_mastermind_approval'] === EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL){
		$needsMastermindApproval = "Sim";
	}else{
		$needsMastermindApproval = "Não";
	}
	echo "<h4><b>Necessita de aprovação do orientador?</b>: ".$needsMastermindApproval."</h3>";

	echo "<br>";
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
					    		// if($disciplineAlreadyExistsInOffer){
					    		// 	echo anchor("offer/removeDisciplineFromOffer/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "Remover disciplina da lista", "class='btn btn-danger'");
					    		// }else{
				    			// 	echo anchor("offer/addDisciplineToOffer/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "Adicionar à lista de oferta de ".$course['course_name'], "class='btn btn-primary'");
					    		// }
								echo anchor("offer/displayDisciplineClasses/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "<i class='fa fa-tasks'></i> Gerenciar turmas para a oferta", "class='btn btn-primary'");
					    	echo "</td>";

					    echo "</tr>";
				    }

			    }else{

			    	echo "<tr>";
				    	echo "<td colspan=5>";
					    	echo "<div class=\"callout callout-warning\">";
                            	echo "<h4>Não há disciplinas cadastradas no currículo deste curso no momento.</h4>";
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

function displayGuestUsers(){
	define("GUEST_GROUP_ID", 8);
	$users = new Usuario();
	$guests = $users->getUsersOfGroup(GUEST_GROUP_ID);

	if($guests !== FALSE){
		echo "<table class=\"table table-bordered table-hover\">";
			echo "<tbody>";
				echo "<h3>Lista de Usuários que podem ser Matriculados</h3>";
				echo "<tr>";
					echo "<th class=\"text-center\">Nome: </th>";
					echo "<th class=\"text-center\">Email: </th>";
				echo "</tr>";
			 foreach ($guests as $keys => $user){
				echo "<tr>";
					echo "<td>";
						echo $user['name'];
					echo "</td>";
					echo "<td>";
						echo $user['email'];
					echo "</td>";

				echo "</tr>";
			 }
			echo "</tbody>";
		echo "</table>";
	}

}

function displayResearchLinesByCourse($research_lines,$courses){
	echo "<br><br>";
	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
			echo "<h3>Linhas de pesquisa por curso</h3>";
			echo "<br>";
			echo anchor("usuario/createCourseResearchLine/","<i class='fa fa-check'></i>   Criar Linha de Pesquisa", "class='btn btn-success'");
			echo "<br><br>";
			echo "<tr>";
				echo "<th class=\"text-center\">Curso: </th>";
				echo "<th class=\"text-center\">Linha de Pesquisa: </th>";
				echo "<th class=\"text-center\">Ações: </th>";
			echo "</tr>";
			foreach ($research_lines as $keys => $researchs){
				if($researchs){
					foreach ($researchs as $researchData){
						echo "<tr>";
							echo "<td>";
								echo $courses[$keys]['course_name'];
							echo "</td>";
							echo "<td>";
								echo $researchData['description'];
							echo "</td>";
							echo "<td>";
								echo anchor("usuario/updateCourseResearchLine/{$researchData['id_research_line']}/{$courses[$keys]['id_course']}","<i class='fa fa-pencil'></i>   Editar Linha de Pesquisa", "class='btn btn-primary'");
								echo anchor("secretary/removeCourseResearchLine/{$researchData['id_research_line']}/{$courses[$keys]['course_name']}", "<i class='fa fa-eraser'></i> Remover Linha de Pesquisa", "class='btn btn-danger'");
							echo "</td>";
						echo "</tr>";
					}
				}else{
					echo "<tr>";
						echo "<td>";
							echo $courses[$keys];
						echo "</td>";
						echo "<td>";
							echo "Não existem linhas de pesquisa cadastradas para este curso";
						echo "</td>";
						echo "<td>";
							echo "Não existem ações possíveis.";
						echo "</td>";
					echo "</tr>";
				}
			}
		echo "</tbody>";
	echo "</table>";

}

function displayDisciplineToResearchLineTable($researchLines, $disciplines, $syllabusId, $courseId){

	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
			echo "<h3>Linhas de pesquisa da disciplina ". $disciplines['discipline_name']."</h3>";
			echo "<tr>";
				echo "<th class=\"text-center\">Linha de Pesquisa: </th>";
				if ($researchLines){
					echo "<th class=\"text-center\">Ações: </th>";
				}
			echo "</tr>";
			if (!$researchLines){
				echo "<tr>";
					echo "<td>";
						echo "Não foi relacionada nenhuma linha de pesquisa";
					echo "</td>";
				echo "</tr>";
			}else{
				foreach ($researchLines as $key => $line){
					echo "<tr>";
						echo "<td>";
							echo $line;
						echo "</td>";
						echo "<td>";
						echo anchor("syllabus/removeDisciplineResearchLine/{$key}/{$disciplines['discipline_code']}/{$syllabusId}/{$courseId}", "<i class='fa fa-eraser'></i> Remover Linha de Pesquisa", "class='btn btn-danger'");
						echo "</td>";

					echo "</tr>";
				}
			}
		echo "</tbody>";
	echo "</table>";

}