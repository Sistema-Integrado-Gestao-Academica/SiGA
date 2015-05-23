
<br>

<h2 align="center">Avaliação do programa <b><i><?php echo $programData['acronym']." - ".$programData['program_name']; ?></i></b></h2>

<br>
<br>

<h3>Dados da avaliação do período <b><?php echo $programEvaluation['start_year']." - ".$programEvaluation['end_year'];?></b>: </h4>

<h3>Dados Gerais:</h3>
<h4>
	<b>Ano atual</b>: <?php echo $programEvaluation['current_year']; ?>
	<br>
	<b>Nota Geral</b>: <?php echo $programEvaluation['general_note']; ?>
</h4>

<hr>

<h3><b>Dimensões de avaliação:</b></h3>

<!-- <div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<tbody>
		    <tr>
		        <th class="text-center">Dimensão</th>
		        <th class="text-center">Ações</th>
		    </tr> -->

		    <?php

			    if($dimensionsTypes !== FALSE){

			    	foreach($dimensionsTypes as $dimension){
			    		// echo "<tr>";
			    		// 	echo "<td>";
			    				// echo $dimension['dimension_type_name'];
			    			// echo "</td>";

			    			// echo "<td>";
			    				echo anchor(
			    					"coordinator/evaluationDimensionData/{$programEvaluation['id_program_evaluation']}/{$dimension['id_dimension_type']}/{$programData['id_program']}",
			    					"<i class='fa fa-eye'></i> Dados da dimensão <h4><b>".$dimension['dimension_type_name']."</b></h4>",
			    					"class='btn btn-primary' btn-flat"
			    				);
			    				echo "<br>";
			    				echo "<br>";
			    		// 	echo "</td>";
			    		// echo "</tr>";
			    	}

			    }else{
			    	// echo "<tr>";
			    	// 	echo "<td colspan=2>";
			    			echo "<div class='callout callout-danger'>";
			    			echo "<h4>Não há dimensões cadastradas.</h4>";
			    			echo "</div>";
			    	// 	echo "</td>";
			    	// echo "</tr>";
			    }
		    ?>
		<!-- </tbody>
	</table>
</div> -->


<br>
<?php echo anchor('coordinator/coordinator_programs', "Voltar", "class='btn btn-danger'");?>