<?php

require_once(MODULESPATH."/program/controllers/Discipline.php");
require_once(MODULESPATH."/program/controllers/Course.php");
require_once(MODULESPATH."/auth/controllers/UserController.php");
require_once(MODULESPATH."/secretary/controllers/Schedule.php");
require_once(MODULESPATH."/secretary/controllers/Offer.php");
require_once(MODULESPATH."/secretary/controllers/Request.php");
require_once(MODULESPATH."/program/controllers/Program.php");
require_once(MODULESPATH."/secretary/controllers/Syllabus.php");
require_once(MODULESPATH."/auth/controllers/Module.php");
require_once(MODULESPATH."/program/controllers/Mastermind.php");
require_once(MODULESPATH."/program/controllers/Coordinator.php");
require_once(MODULESPATH."/auth/constants/GroupConstants.php");
require_once(MODULESPATH."/secretary/constants/EnrollmentConstants.php");
require_once(MODULESPATH."/secretary/constants/OfferConstants.php");

require_once(APPPATH."/data_types/view_types/AlertCallout.php");
require_once(APPPATH."/data_types/view_types/WrapperCallout.php");

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

/**
 * Builds a callout(from AdminLTE) hmtl code
 * @param $calloutType - The type of the callout (Info, Warning, Danger). The default is the info callout.
 * @param $principalMessage - The principal message on the callout. Goes in HTML 'h4' tags.
 * @param $aditionalMessage - The aditionalMessage for the callout. Goes in HTML 'p' tags. Optional.
 * @param $calloutId - The html id for the callout. Optional.
 */
function callout($calloutType = "info", $principalMessage, $aditionalMessage = FALSE, $calloutId = FALSE){

	$callout = new AlertCallout($calloutType, $principalMessage, $aditionalMessage, $calloutId);

	$callout->draw();
}

/**
 * Builds a callout(from AdminLTE) hmtl code
 * @param $calloutType - The type of the callout (Info, Warning, Danger). The default is the info callout.
 * @param $wrapperContent - The content that goes inside the callout
 * @param $principalMessage - The principal message on the callout. Goes in HTML 'h4' tags.
 * @param $aditionalMessage - The aditionalMessage for the callout. Goes in HTML 'p' tags. Optional.
 * @param $calloutId - The html id for the callout. Optional.
 */
function wrapperCallout($calloutType = "info", $wrapperContent = FALSE, $principalMessage = FALSE, $aditionalMessage = FALSE, $calloutId = FALSE){

	$wrapperCallout = new WrapperCallout($calloutType, $wrapperContent, $principalMessage, $aditionalMessage, $calloutId);

	return $wrapperCallout;
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


function showMastermindsStudents($masterminds){

	$coordinator = new Coordinator();

	echo "<div class=\"col-lg-12 col-xs-6\">";
		echo "<div class='panel panel-primary'>";
			echo "<div class='panel-heading'><h4>Relação de Alunos por Professores: </h4></div>";
			echo "<div class='panel-body'>";
				echo "<div class=\"modal-info\">";
					echo "<div class=\"modal-content\">";
	if($masterminds !== FALSE){
		foreach ($masterminds as $key => $mastermind){
			$students = $coordinator->getMastermindStudents($mastermind['id_user']);

			$userData = new UserController();
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
	}else{

		$message = "Não há alunos relacionados a nenhum professor.";
		callout("info", $message);
	}

					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";


}

function secretaryCoursesToRequestReport($courses){

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
	    		echo anchor("secretary/request/courseRequests/{$courseId}","<i class='fa fa-plus-square'>Visualizar Solicitações</i>", "class='btn btn-primary'");
	    		echo "</td>";
			echo "</tr>";
		}
	}else{
		echo "<tr>";
		echo "<td colspan=4>";
			$message = "Não há cursos cadastrados para este secretário.";
			callout("info", $message);
		echo "</td>";
		echo "</tr>";
	}

	buildTableEndDeclaration();
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

function displayCourseRequests($requests, $courseId, $users){

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

	echo anchor("secretary/request/courseRequests/{$courseId}", "Visualizar todas", "class='btn bg-olive btn-flat' style='margin-bottom:1%;'");

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código da requisição',
		'Aluno requerente',
		'Matrícula aluno',
		'Status da solicitação',
		'Ações'
	));

    if($requests !== FALSE){

    	foreach($requests as $request){

    		$requestId = $request['id_request'];

    		echo "<tr>";

    		echo "<td>";
    		echo $requestId;
    		echo "</td>";

    		if($users !== FALSE){
	    		echo "<td>";
	    		echo $users[$requestId]['name'];
	    		echo "</td>";

	    		echo "<td>";
	    		echo $users[$requestId]['enrollment'];
	    		echo "</td>";
    		}

    		echo "<td>";
    			$status = switchRequestGeneralStatus($request['request_status']);
    			echo $status;
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

    		$requestIsApprovedByMastermind = $request['mastermind_approval'] == EnrollmentConstants::REQUEST_APPROVED_BY_MASTERMIND;
    		$requestIsNotFinalizedBySecretary = $request['secretary_approval'] != EnrollmentConstants::REQUEST_APPROVED_BY_SECRETARY;

    		if($requestIsApprovedByMastermind){

    			if($requestIsNotFinalizedBySecretary){

		    		if($request['request_status'] === EnrollmentConstants::REQUEST_ALL_APPROVED_STATUS){
		    			// In this case all request is already approved
		    		}else{
		    			echo "<br>";
		    			echo anchor("secretary/request/approveAllRequest/{$requestId}/{$courseId}", "Aprovar toda solicitação", "class='btn btn-success' style='margin-top:5%;'");
		    		}

		    		echo "<br>";

		    		if($request['request_status'] === EnrollmentConstants::REQUEST_ALL_REFUSED_STATUS){
		    			// In this case all request is already refused
		    		}else{
		    			echo "<br>";
		    			echo anchor("secretary/request/refuseAllRequest/{$requestId}/{$courseId}", "Recusar toda solicitação", "class='btn btn-danger'");
		    		}

		    		echo "<br>";
		    		echo "<div class=\"callout callout-info\">";
		    			echo anchor("secretary/request/finalizeRequestSecretary/{$requestId}/{$courseId}", "Finalizar solicitação", "class='btn btn-primary btn-flat' style='margin-top: 5%;'");
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
	    		echo "<div class='collapse' id='solicitation_details_".$requestId."'>";
				requestedDisciplineClasses($requestId, EnrollmentConstants::REQUESTING_AREA_SECRETARY);
	    		echo "</div>";
    		echo "</td>";

    		echo "</tr>";
    	}
    }else{
		echo "<tr>";
    	echo "<td colspan=5>";
    		$message = "Nenhuma solicitação encontrada.";
			callout("info", $message);
		echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();
}

function requestedDisciplineClasses($requestId, $requestingArea){

	$requestController = new Request();

	$request = $requestController->getRequestById($requestId);
	$requestDisciplines = $requestController->getRequestDisciplinesClasses($requestId);
	$courseId = $requestController->getCourseIdByIdRequest($requestId);

	$discipline = new Discipline();

	echo "<div class='panel panel-info'>";

		echo "<div class='panel-heading'>Disciplinas solicitadas</div>";

		buildTableDeclaration();

		buildTableHeaders(array(
			'Código Disciplina',
			'Disciplina requerida',
			'Turma requerida',
			'Vagas totais',
			'Vagas disponíveis',
			'Status',
			'Ações'
		));

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
							callout("info", "Disciplina não encontrada.");
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
											echo anchor("secretary/request/approveRequestedDisciplineSecretary/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
										}else{
											echo "<div class=\"callout callout-danger\">";
											echo "<h6>Recusado pelo orientador. Sem ações.</h6>";
											echo "</div>";
										}
									}

									if($disciplineClass['status'] === EnrollmentConstants::REFUSED_STATUS){
										// In this case the request was already refused
									}else{
										echo anchor("secretary/request/refuseRequestedDisciplineSecretary/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Recusar", "class='btn btn-danger btn-flat'");
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
										echo anchor("secretary/request/approveRequestedDisciplineMastermind/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
									}

									if($disciplineClass['status'] === EnrollmentConstants::REFUSED_STATUS){
										// In this case the request was already refused
									}else{
										echo anchor("secretary/request/refuseRequestedDisciplineMastermind/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Recusar", "class='btn btn-danger btn-flat'");
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
				callout("warning", "Não foram encontradas disciplinas para essa solicitação.");
			echo "</td>";
		}

	buildTableEndDeclaration();
}

function requestedDisciplineClassesForMastermind($requestId, $idMastermind, $idStudent){

	$requestController = new Request();
	$requestDisciplines = $requestController->getRequestDisciplinesClasses($requestId);
	$courseId = $requestController->getCourseIdByIdRequest($requestId);
	$discipline = new Discipline();

	echo "<div class='panel panel-info'>";

	echo "<div class='panel-heading'>Disciplinas solicitadas</div>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código Disciplina',
		'Disciplina requerida',
		'Turma requerida',
		'Vagas totais',
		'Vagas disponíveis',
		'Status',
		'Ações'
	));

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
			}
			else{
				displayAcceptStudentDisciplineSolicitation($requestId, $disciplineClass['id_offer_discipline'], $courseId, $idMastermind, $idStudent);
				// echo anchor("secretary/request/approveRequestedDiscipline/{$requestId}/{$disciplineClass['id_offer_discipline']}/{$courseId}", "Aprovar", "class='btn btn-primary btn-flat' style='margin-bottom: 5%;'");
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
	}

	buildTableEndDeclaration();
}


function displaySentDisciplinesToEnrollmentRequest($requestDisciplinesClasses){

	$discipline = new Discipline();

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Disciplina',
		'Turma',
		'OBS'
	));

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
    		callout("info", "Nenhuma disciplina adicionada para solicitação de matrícula.");
		echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();
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

	$discipline = new Discipline();

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Disciplina',
		'Turma',
		'Horário',
		'Ações'
	));

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
		    				"student/temporaryrequest/removeDisciplineFromTempRequest/{$userId}/{$courseId}/{$semesterId}/{$foundDiscipline['discipline_code']}/{$foundClass['class']}",
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
		    			callout("info", "Não foi encontrada a turma informada.");
		    		echo "</td>";
	    		echo "</tr>";
    		}
    	}
    }else{
		echo "<tr>";
    	echo "<td colspan=5>";
    		callout("info", "Nenhuma disciplina adicionada para solicitação de matrícula.");
		echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();
}

function displayDisciplineClasses($disciplineClasses){

	$user = new UserController();

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
		callout("info", "Nenhuma turma cadastrada para oferta.");
	}
}

function displayOfferListDisciplines($offerListDisciplines, $courseId){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Disciplina',
		'Créditos'
	));

    if($offerListDisciplines != FALSE){

    	foreach($offerListDisciplines as $discipline){

			echo "<tr>";
	    		echo "<td>";
	    		echo $discipline['discipline_code'];
	    		echo "</td>";

	    		echo "<td>";
	    		echo anchor("program/discipline/displayDisciplineClassesToEnroll/{$courseId}/{$discipline['discipline_code']}", "<b>".$discipline['discipline_name']." - ".$discipline['name_abbreviation']."</b>");
	    		echo "</td>";

	    		echo "<td>";
	    		echo $discipline['credits'];
	    		echo "</td>";
    		echo "</tr>";
    	}
    }else{
		echo "<tr>";
    	echo "<td colspan=3>";
			callout("info", "Nenhuma lista de oferta cadastrada para o semestre atual.");
		echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();
}

function displayOfferDisciplineClasses($idDiscipline, $idOffer, $offerDisciplineClasses, $teachers, $idCourse){

	if($offerDisciplineClasses !== FALSE){

		$user = new UserController();

		foreach($offerDisciplineClasses as $class){

			$mainTeacher = $user->getUserById($class['main_teacher']);

			if($class['secondary_teacher'] !== NULL){
				$secondaryTeacher = $user->getUserById($class['secondary_teacher']);
				$secondaryTeacher = $secondaryTeacher['name'];
			}else{
				$secondaryTeacher = "-";
			}

			buildTableDeclaration();

			buildTableHeaders(array(
				'Turma',
				'Vagas totais',
				'Vagas atuais',
				'Professor principal',
				'Professor secundário',
				'Horários',
				'Ações'
			));

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
    			echo anchor("secretary/offer/formToUpdateDisciplineClass/{$idOffer}/{$idDiscipline}/{$class['class']}/{$idCourse}","Editar turma", "class='btn btn-warning' style='margin-right:5%; margin-bottom:10%;'");
    			echo anchor("secretary/offer/deleteDisciplineClass/{$idOffer}/{$idDiscipline}/{$class['class']}/{$idCourse}","Remover turma", "class='btn btn-danger'");
		    	echo "</td>";

		    echo "</tr>";

			buildTableEndDeclaration();
		}

		formToNewOfferDisciplineClass($idDiscipline, $idOffer, $teachers, $idCourse);

	}else{

		callout("info", "Nenhuma turma cadastrada no momento.", "Cadastre logo abaixo.");

		formToNewOfferDisciplineClass($idDiscipline, $idOffer, $teachers, $idCourse);
	}
}

function displayDisciplineHours($idOfferDiscipline){

	$schedule = new Schedule();
	$schedule->getDisciplineHours($idOfferDiscipline);
	$disciplineSchedule = $schedule->getDisciplineSchedule();

	if(sizeof($disciplineSchedule) > 0){

		buildTableDeclaration();

		buildTableHeaders(array(
			'Dia-Horário',
			'Local'
		));

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

		buildTableEndDeclaration();

	}else{
		callout("info", "Sem horários adicionados no momento.");
	}
}

function drawFullScheduleTable($offerDiscipline, $idCourse){

	$schedule = new Schedule();

	$schedule->drawFullSchedule($offerDiscipline, $idCourse);
}

function formToUpdateOfferDisciplineClass($disciplineId, $idOffer, $teachers, $offerDisciplineClass, $idCourse){

	
	echo "<br>";
	echo "<br>";
	echo "<h3><i class='fa fa-clock-o'></i> Gerenciar horários da turma</h3>";
	echo "<br>";
	drawFullScheduleTable($offerDisciplineClass, $idCourse);

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

	echo form_open("secretary/offer/updateOfferDisciplineClass/{$disciplineId}/{$idOffer}/{$offerDisciplineClass['class']}");

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
			"secretary/offer/displayDisciplineClasses/{$disciplineId}/{$idOffer}/{$idCourse}",
			"Voltar",
			"class='btn bg-olive btn-block'"
		);
		echo "</div>";

		echo "</div>";
	echo "</div>";

	echo "</div>";

	echo form_close();

	if($teachers === FALSE){
		callout("danger", "Não é possível alterar uma turma para que fique sem um professor principal.", "Contate o administrador.");
	}

}

function displayRegisteredCoursesToProgram($programId, $courses){

	$program = new Program();

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código do curso',
		'Curso',
		'Ações'
	));

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
			callout("info", "Nenhum curso cadastrado.");
		echo "</td>";
    }

	buildTableEndDeclaration();
}

function displayRegisteredPrograms($programs, $canRemove){

	if(!empty($programs)){
		buildTableDeclaration();

		buildTableHeaders(array(
			'Programas cadastrados',
			'Ações'
		));

		if($programs !== FALSE){

			foreach($programs as $program){
				echo "<tr>";

					echo "<td>";
						echo $program['program_name']." - ".$program['acronym'];
					echo "</td>";

					echo "<td>";

					$summaryNonExists = isEmpty($program['summary']);
					$historyNonExists = isEmpty($program['history']);
					$contactNonExists = isEmpty($program['contact']);

					if($summaryNonExists || $historyNonExists || $contactNonExists){
						echo "<span class='label label-warning' data-container=\"body\" data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		     				data-content=\"Edite o programa e complemente com os dados de resumo, contato, histórico e linhas de 	pesquisa.\" id='alert' ><i class='fa fa-warning'></i> Dados Incompletos </span>";
		    		}
		    			echo "<br><br>";
						echo anchor("program/editProgram/{$program['id_program']}", "<span class='glyphicon glyphicon-edit'></span>", "class='btn btn-primary' style='margin-right: 5%' id='edit_program_btn' data-container=\"body\"
		     				data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		     				data-content=\"Aqui é possível editar os dados do programa e adicionar cursos a ele.\"");

					if ($canRemove) {

						echo anchor("program/removeProgram/{$program['id_program']}", "<span class='glyphicon glyphicon-remove'></span>", "class='btn btn-danger' id='remove_program_btn' data-container=\"body\"
		     				data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
		     				data-content=\"OBS.: Ao deletar um programa, todos os cursos associados a ele serão desassociados.\"");
					}

					echo "</td>";

				echo "</tr>";
			}

		}else{
			echo "<td colspan=2>";
				$wrapperContent = anchor("program/registerNewProgram", "Cadastrar Programa", "class='btn btn-primary'");
				$callout = wrapperCallout("info", $wrapperContent, "Não existem programas cadastrados.");
				$callout->draw();
			echo "</td>";
		}

		buildTableEndDeclaration();
	}
	else{
		echo "<td colspan=2>";
			callout("info", "Nenhum programa cadastrado");
		echo "</td>";
	}
}

function displayCoordinatorPrograms($programs){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Programas cadastrados',
		'Ações'
	));

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
    		callout("info", "Não existem programas cadastrados.");
		echo "</td>";
    }

	buildTableEndDeclaration();
}

function displayProgramCourses($programId, $courses){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código do curso',
		'Curso',
		'Ações'
	));

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
			callout("info", "Nenhum curso cadastrado.");
		echo "</td>";
    }

	buildTableEndDeclaration();
}

function displayCourseStudents($courseId, $students){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código do aluno',
		'Aluno',
		'E-mail',
		'Ações'
	));

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
			callout("info", "Nenhum aluno matriculado neste curso.");
		echo "</td>";
    }

    buildTableEndDeclaration();
}

function displaySyllabusDisciplinesToStudent($syllabusDisciplines){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código da Disciplina',
		'Disciplina',
		'Créditos',
		'Carga-horária'
	));

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
			callout("info", "Nenhuma disciplina no currículo deste curso.");
		echo "</td>";
    	echo "</tr>";
    }

	buildTableEndDeclaration();
}

function displayDisciplinesToSyllabus($syllabusId, $allDisciplines, $courseId){

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Sigla',
		'Disciplina',
		'Créditos',
		'Linhas de pesquisa',
		'Ações'
	));

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
    		callout("warning", "Nenhuma disciplina encontrada.");
    	echo "</td>";
		echo "</tr>";
    }

    buildTableEndDeclaration();
}

function displayOffersList($offers, $currentSemester, $nextSemester){
	
	$course = new Course();

	buildTableDeclaration();

	buildTableHeaders(array(
		'Curso',
		"Semestre atual - ".$currentSemester['description'],
		"Semestre seguinte - ".$nextSemester['description']
	));

	    foreach($offers as $courseName => $offer){

	    	$foundCourse = $course->getCourseByName($courseName);
			$courseId = $foundCourse['id_course'];

	    	echo "<tr>";

	    		echo "<td>";
	    			echo $courseName;
	    		echo "</td>";

		    	echo "<td>";
					$currentOffer = $offer['current_semester'];
					if($currentOffer !== FALSE){
		    			displayOfferListBySemester($currentOffer, $courseId);

		    		}
	    			else{
	    				formToAddNewOffer(OfferConstants::PROPOSED_OFFER, $currentOffer, $courseId, $currentSemester);
			    	}
	    		echo "</td>";

	    		echo "<td>";
			    	$nextOffer = $offer['next_semester'];
			    	if($nextOffer !== FALSE){
		    			displayOfferListBySemester($nextOffer, $courseId);
					}
					else{	
	    				formToAddNewOffer(OfferConstants::PLANNED_OFFER, $currentOffer, $courseId, $nextSemester);
	    			}
	    		echo "</td>";

	    	echo "</tr>";
	    }

	buildTableEndDeclaration();
}

function displayOfferListBySemester($offer, $courseId){

	$status = $offer['offer_status'];

	if($status === OfferConstants::APPROVED_OFFER){
		$content = anchor("", "<i class='fa fa-edit'></i>", "class='btn btn-danger disabled'");
	    $principalMessage = "Lista de ofertas aprovada";
	    $aditionalMessage = "<b><i>Somente as listas de ofertas com status \"proposta\" podem ser alteradas.</i><b/>";
	}
	else{
		$content = anchor("secretary/offer/displayDisciplines/{$offer['id_offer']}/{$courseId}","<i class='fa fa-edit'></i>", "class='btn btn-danger'");
		$principalMessage = "Editar Oferta ".lang($status);
	    $aditionalMessage = "<b><i>Aqui é possível adicionar <br>disciplinas a lista de oferta.</i><b/>";
	}

	$callout = wrapperCallout("info", $content, $principalMessage, $aditionalMessage);
	$callout->draw();
}

function formToAddNewOffer($status, $offer, $courseId, $semester){
	
	if($status == OfferConstants::PLANNED_OFFER){
		$btn_content = "Planejar";
	}
	else{
		$btn_content = "Criar";
	}

	$newOfferBtn = array(
		"id" => "new_offer_btn",
		"class" => "btn btn-primary",
		"content" =>  $btn_content." lista de ofertas",
		"type" => "submit"
	);

	$needsMastermindApprovalCheckBox = array(
	    'name' => 'needs_mastermind_approval_ckbox',
	    'id' => 'needs_mastermind_approval_ckbox',
	    'value' => EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL,
	    'checked' => TRUE,
	    'style' => 'margin:15px',
    );

	    $status_pt = lang($status);
		$principalMessage = "Nenhuma lista de ofertas ".$status_pt." para o semestre ".$semester['description'];
		$aditionalMessage = "<b><i>OBS.: A lista de oferta será criada para o semestre </i><b/>".$semester['description'];
		$callout = wrapperCallout("info", FALSE, $principalMessage, $aditionalMessage);

		$callout->writeCalloutDeclaration();
		$callout->writePrincipalMessage();

		echo form_open("secretary/offer/newOffer/{$courseId}");
		echo form_checkbox($needsMastermindApprovalCheckBox);
		echo form_hidden("semester", $semester['id_semester']);
		echo form_hidden("status", $status);
		echo form_label('Necessita de aprovação do orientador.', 'needs_mastermind_approval_ckbox');
		echo "<br>";
		echo form_button($newOfferBtn);
		echo form_close();

		$callout->writeAditionalMessage();
		$callout->writeCalloutEndDeclaration();
}

function displayOfferDisciplines($idOffer, $course, $disciplines){

	$offer = new Offer();
	$offerData = $offer->getOffer($idOffer);

	echo "<h2 class='principal'>Lista de Oferta</h2>";
	echo "<h3><b>Curso</b>: ".$course['course_name']."</h3>";

	if($offerData['needs_mastermind_approval'] === EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL){
		$needsMastermindApproval = "Sim.";
	}else{
		$needsMastermindApproval = "Não.";
	}
	echo "<h4><b>Necessita de aprovação do orientador?</b>: ".$needsMastermindApproval."</h3>";

	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		"Código da Lista: ".$idOffer,
		'Status: Proposta'
	));

    echo "<tr>";
    	echo "<td colspan=2> <b>Disciplinas</b> </td>";
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
            echo anchor("secretary/offer/addDisciplines/{$idOffer}/{$course['id_course']}",'Adicionar disciplinas', "class='btn btn-primary'");
            echo "</td>";
	    echo "</tr>";
    }else{

    	echo "<tr>";
    	echo "<td colspan=2>";
            $content = anchor("secretary/offer/addDisciplines/{$idOffer}/{$course['id_course']}",'Adicionar disciplinas', "class='btn btn-primary'");
  			$principalMessage = "Nenhuma disciplina adicionada a essa lista de oferta no momento.";
    		$callout = wrapperCallout("info", $content, $principalMessage);
    		$callout->draw();
    	echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();

	echo "<div class=\"row\">";
		echo "<div class=\"col-xs-3\">";
			$status = $offerData['offer_status'];
			if($status === OfferConstants::PROPOSED_OFFER){
				if($disciplines !== FALSE){

					echo anchor("secretary/offer/approveOfferList/{$idOffer}", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
			             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\"
			             data-content=\"OBS.: Ao aprovar a lista de oferta não é possível adicionar ou retirar disciplinas.\"");
				}
				else{
						echo anchor("", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
				             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\" disabled='true'
				             data-content=\"Não é possível aprovar uma lista sem disciplinas.\"");
				}
			}
		echo "</div>";
		echo "<div class=\"col-xs-3\">";
			echo anchor("offer_list", "Voltar", "class='btn btn-danger'");
		echo "</div>";
	echo "</div>";
}

function displayUserGroups($idUser, $userGroups){

	$ci =& get_instance();
	$ci->load->model("auth/usuarios_model");
	$foundUser = $ci->usuarios_model->getUserById($idUser);
	echo "<h3>Grupos pertencentes a <b>".$foundUser['name']."</b>:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Grupo',
		'Ações'
	));

    if($userGroups !== FALSE){

	    foreach($userGroups as $group){

	    	echo "<tr>";

		    	echo "<td>";
		    		echo $group['group_name'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo anchor("auth/userController/removeUserGroup/{$idUser}/{$group['id_group']}", "Remover Grupo", "class='btn btn-danger'");
		    	echo "</td>";

	    	echo "</tr>";
	    }

    }else{
    	echo "<tr>";
    	echo "<td colspan=2>";
    		callout("warning", "Não há grupos cadastrados para esse usuário.");
    	echo "</td>";
		echo "</tr>";
    }

    buildTableEndDeclaration();
}

function displayAllGroupsToUser($idUser, $allGroups, $userGroups){

	$ci =& get_instance();
	$ci->load->model("auth/usuarios_model");
	$foundUser = $ci->usuarios_model->getUserById($idUser);
		
	echo "<h3>Grupos Existentes:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Grupo',
		'Ações'
	));

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
	    				echo anchor("auth/userController/addGroupToUser/{$idUser}/{$idGroup}", "<i class='fa fa-plus'></i> <i class='fa fa-user'></i> <b>".$foundUser['name']."</b>", "class='btn btn-primary'");
		    		}
		    	echo "</td>";

	    	echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=2>";
    		callout("warning", "Não há grupos cadastrados no sistema no momento.");
    	echo "</td>";
		echo "</tr>";
    }

    buildTableEndDeclaration();
}

function displayUsersOfGroup($idGroup, $usersOfGroup){

	$ci =& get_instance();
	$ci->load->model("module_model");
	$foundGroup = $ci->module_model->getGroupById($idGroup);
	echo "<h3>Usuários do grupo <b>".$foundGroup['group_name']."</b>:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Nome',
		'CPF',
		'E-mail',
		'Ações'
	));

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
		    		echo anchor("auth/userController/removeUserFromGroup/{$user['id']}/{$idGroup}", "<i class='fa fa-eraser'></i> Remover Usuário", "class='btn btn-danger'");
		    	echo "</td>";

	    	echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=5>";
    		callout("warning", "Não há usuários cadastrados nesse grupo no momento.");
    	echo "</td>";
		echo "</tr>";
    }

    buildTableEndDeclaration();
}

function displayResearchLinesByCourse($research_lines,$courses){

	echo "<h3 class='principal'>Linhas de pesquisa por curso</h3>";

	echo anchor("program/course/createCourseResearchLine/","<i class='fa fa-plus-circle'></i>   Criar Linha de Pesquisa", "class='btn-lg'");

	if($research_lines !== FALSE){
		buildTableDeclaration();

		buildTableHeaders(array(
			'Curso',
			'Linhas de Pesquisa',
			'Ações'
		));

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
							echo anchor("program/course/updateCourseResearchLine/{$researchData['id_research_line']}/{$courses[$keys]['id_course']}","<i class='fa fa-pencil'></i>   Editar Linha de Pesquisa", "class='btn btn-primary'");
							echo anchor("program/course/removeCourseResearchLine/{$researchData['id_research_line']}/{$courses[$keys]['course_name']}", "<i class='fa fa-eraser'></i> Remover Linha de Pesquisa", "class='btn btn-danger'");
						echo "</td>";
					echo "</tr>";
				}
			}else{
				echo "<tr>";
					echo "<td>";
						echo $courses[$keys]['course_name'];
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
	}else{
		callout("info", "Não foram encontradas linhas de pesquisa para este curso.");
	}

	buildTableEndDeclaration();
}

function displayGuestForEnrollment($guests, $courseId){
	buildTableDeclaration();

	buildTableHeaders(array(
		'Nome',
		'E-mail',
		'Ações'
	));

	$markUnknownUser = array(
		"id" => "mark_user_as_unknown_btn",
		"class" => "btn bg-warning",
		"content" => "Marcar como desconhecido",
		"type" => "submit"
	);
	foreach ($guests as $user){

		?> 
		<tr>

			<td>
				<?= $user['name'] ?>
			</td>
			<td>
				<?= $user['email'] ?>
			</td>
			<td>
				<?php
				
				echo anchor("secretary/enrollment/enrollStudent/{$courseId}/{$user['id']}", "Matricular", "class='btn btn-primary'"); 

				?>
				
				<button data-toggle="collapse" data-target=<?="#confirmation".$user['id']?> class="btn btn-warning" >
					<span class="fa fa-user"></span><span class="fa fa-question-circle"></span> Desconhecido 
				</button>
				
				<div id=<?="confirmation".$user['id']?> class="collapse">
					<?= form_open("secretary/enrollment/setUserAsUnknown") ?>
					<?= form_hidden("course", $courseId) ?>
					<?= form_hidden("user", $user['id']) ?>
					<br>
					Não conhece este usuário? Marque-o como desconhecido.
					<br>
					<?= form_button($markUnknownUser) ?>
					<?= form_close() ?>
				</div>
			</td>
		</tr>
	<?php
	}

	buildTableEndDeclaration();

}