<br>
<h2>Dados da dimensão <b><i><?php echo $dimensionName; ?></i></b></h2>

<br>

<div class="row">
<div class="col-lg-3">
	<h4>Peso da dimensão: <b><?php echo $dimensionData['weight']; ?>%</b></h4>
</div>
<div class="col-lg-3">
	<?php 

		echo anchor(
			"coordinator/disableDimension/{$evaluationData['id_program_evaluation']}/{$dimensionData['id_dimension_type']}/{$dimensionData['id_dimension']}",
		 	"Desativar dimensão",
		 	"class='btn btn-danger'"
		);

	?>
</div>
</div>

<h4>Nota geral: <b><?php echo $dimensionData['indicators_note']; ?></b></h4>
