
<br>

<?php

for ($i=0; $i<sizeof($offerDisciplineData); $i++){
	
	$equalClasses = strcmp($offerDisciplineData[$i]['class'], $class);
	
	/**
	 * The strcmp() function returns 0 if strings are identical
	 */
	
	if ($equalClasses == 0){
		formToUpdateOfferDisciplineClass($disciplineData['discipline_code'],$idOffer,$teachers, $offerDisciplineData[$i]);
		break;
	}else{
		$status = "danger";
		$message = "Não foi possível editar esta turma da lista de ofertas.";
		$this->session->set_flashdata($status, $message);
		redirect("offer/displayDisciplineClasses/{$disciplineData['discipline_code']}/{$idOffer}");
	}
}