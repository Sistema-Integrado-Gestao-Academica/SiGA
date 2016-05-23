<?php

	echo "<br>";
	echo "<h3>Solicitações dos alunos orientados:</h3>";
	echo "<br>";

	buildTableDeclaration();

	buildTableHeaders(array(
		'Código da requisição',
		'Aluno requerente',
		'Matrícula aluno',
		'Status da solicitação',
		'Ações',
		'Finalizar'
	));

	if($requests !== FALSE){

		$this->load->model("secretary/offer_model");

		foreach($requests as $request){

			if ($request !== FALSE){

				foreach ($request as $studentRequest){

					$requestId = $studentRequest['id_request'];

					$semesterId = $studentRequest['id_semester'];
					$courseId = $studentRequest['id_course'];
					$requestedOffer = $this->offer_model->getOfferBySemesterAndCourse($semesterId, $courseId);

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

					$this->load->model("auth/usuarios_model");
					$foundUser = $this->usuarios_model->getUserById($studentRequest['id_student']);
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
						echo anchor("secretary/request/approveAllStudentRequestsByMastermind/{$requestId}/{$studentRequest['id_student']}", "Aprovar toda solicitação", "class='btn btn-success' style='margin-top:5%;'");
						echo "<br>";
						echo anchor("secretary/request/refuseAllStudentRequestsByMastermind/{$requestId}/{$studentRequest['id_student']}", "Recusar toda solicitação", "class='btn btn-danger' style='margin-top:5%;'");
					}

					echo "</td>";

					echo "<td rowspan=2>";

						if($requestIsApprovedByMastermind){

							if($needsMastermindApproval){

								$this->load->module("program/mastermind");

								$message = $this->mastermind->getMastermindMessage($idMastermind, $requestId);

								$isFinalized = TRUE;

								$aditionalMessage = "<i>Solicitação finalizada. É possível alterar a mensagem deixada para o aluno.</i>";

								$callout = wrapperCallout("warning", FALSE, FALSE, $aditionalMessage);

								$callout->writeCalloutDeclaration();
								mastermindMessageForm($requestId, $idMastermind, $isFinalized, $message);
								$callout->writeCalloutEndDeclaration();

							}else{
								callout("warning","","<i>O tipo da oferta não permite a ação do orientador.</i>");
							}

						}else{
							$isFinalized = FALSE;
							$aditionalMessage = "<i>Finaliza a solicitação com o status atual das disciplinas.</i>";

							$callout = wrapperCallout("info", FALSE, FALSE, $aditionalMessage);
							$callout->writeCalloutDeclaration();
							mastermindMessageForm($requestId, $idMastermind, $isFinalized);
							$callout->writeCalloutEndDeclaration();
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
			callout("info", "Nenhuma solicitação encontrada.");
			echo "</td>";
		echo "</tr>";
	}

	buildTableEndDeclaration();