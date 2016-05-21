
<br>

<?php
	if($disciplineData !== FALSE){
?>
<h3> Turmas cadastradas para a disciplina <i><b><?php echo $disciplineData['discipline_name']; ?></b></i>:</h3>

<br>

<?php
		displayOfferDisciplineClasses($disciplineData['discipline_code'], $idOffer, $offerDisciplineData, $teachers, $idCourse);

	}else{
?>
	<div class="callout callout-danger">
		<h4>O código da disciplina informado não foi encontrado. Por favor contate o administrador.</h4>
	</div>
<?php
	}
?>

<?= anchor("secretary/offer/addDisciplines/{$idOffer}/{$idCourse}", 'Voltar', "class='btn btn-danger'")?>
