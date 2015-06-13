<br>
<h2>Dados da dimensão <b><i><?php echo $dimensionName; ?></i></b></h2>

<br>

<h4>Nota geral: <b><?php echo $dimensionData['indicators_note']; ?></b></h4>

<div class="row">
<div class="col-lg-3">
	<h4>Peso da dimensão: <b><?php echo $dimensionData['weight']; ?>%</b></h4>
</div>
<div class="col-lg-3">
	<?php
		echo anchor(
			"coordinator/disableDimension/{$evaluationData['id_program_evaluation']}/{$dimensionData['id_dimension_type']}/{$dimensionData['id_dimension']}/{$programId}",
		 	"Desativar dimensão",
		 	"class='btn btn-danger'"
		);
	?>
</div>
<div class="col-lg-3">
	<?php
		echo anchor(
			"#change_dimension_weight_form",
		 	"Alterar peso da dimensão",
		 	"class='btn btn-primary'
			data-toggle='collapse'
			aria-expanded='false'
			aria-controls='change_dimension_weight_form'"
		);
	?>

	<div class="collapse" id="change_dimension_weight_form">
		<?php


			$weight = array(
				'name' => 'dimension_new_weight',
				'id' => 'dimension_new_weight',
				"class" => "form-control",
				"type" => "number",
				"required" => TRUE,
				"min" => 0,
				"step" => 0.01
			);

			$submitBtn = array(
				"class" => "btn bg-olive btn-flat",
				"type" => "submit",
				"content" => "Salvar novo peso"
			);

			define("MAX_WEIGHT", 100);
			if($weightsSum == MAX_WEIGHT){
				$weight['disabled'] = TRUE;
				$submitBtn['disabled'] = TRUE;

				$message = "Todas as dimensões já utilizam 100% no total. Não é possível alterar o peso.";
			}else{
				$availableWeight = MAX_WEIGHT - $weightsSum;
				$currentWeight = $dimensionData['weight'];
				
				if($availableWeight >= $currentWeight){
					$maxWeight = $availableWeight;	
				}else{
					$maxWeight = $currentWeight;
				}

				$weight['max'] = $maxWeight;

				$message = "O peso máximo possível é <b>".$maxWeight."%</b>";
			}

			echo "<br>";
			echo "<div class='callout callout-warning'>";
			echo $message;
			echo "</div>";

			echo form_open("coordinator/changeDimensionWeight");
				echo form_hidden(array(
					'dimensionId' => $dimensionData['id_dimension'],
					'programEvaluationId' => $evaluationData['id_program_evaluation'],
					'dimensionType' => $dimensionData['id_dimension_type'],
					'programId' => $programId
				));

				echo "<br>";
				echo form_label("Informe o novo peso:","dimension_new_weight");
				echo form_input($weight);

				echo "<br>";
				echo form_button($submitBtn);
			echo form_close();
			
		?>
	</div>
</div>
</div>


<br>
<?php echo anchor("coordinator/program_evaluation_index/{$programId}/{$evaluationData['id_program_evaluation']}", "Voltar", "class='btn btn-danger'"); ?>