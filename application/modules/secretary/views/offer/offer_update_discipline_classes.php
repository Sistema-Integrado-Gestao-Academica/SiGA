
<br>

<?php

$session = getSession();

	if($disciplineData !== FALSE){
		if($offerDisciplineData !== FALSE){
			formToUpdateOfferDisciplineClass($disciplineData['discipline_code'], $idOffer, $teachers, $offerDisciplineData, $idCourse);
		}
		else{
			$status = "danger";
			$message = "Não foi possível recuperar os dados desta turma. Tente novamente.";
			$session->showFlashMessage($status, $message);
			redirect("secretary/offer/displayDisciplineClasses/{$disciplineData['discipline_code']}/{$idOffer}/{$idCourse}");
		}

		echo anchor(
			"secretary/offer/displayDisciplineClasses/{$disciplineData['discipline_code']}/{$idOffer}/{$idCourse}",
			"Voltar",
			"class='btn btn-danger'"
		);
	}else{
?>
	<div class="callout callout-danger">
		<h4>O código da disciplina informado não foi encontrado. Por favor contate o administrador.</h4>
	</div>
<?php
	}
?>
