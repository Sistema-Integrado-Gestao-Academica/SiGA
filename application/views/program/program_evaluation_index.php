
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


<!-- <div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<tbody>
		    <tr>
		        <th class="text-center">Dimensão</th>
		        <th class="text-center">Ações</th>
		    </tr> -->
    <div class="row">
		<div class="col-lg-6">
		<h3><b>Dimensões de avaliação do programa <?php echo $programData['acronym']; ?>:</b></h3>
			
		    <?php

		    	$dimensionsOfProgram = array();
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
			    		$dimensionsOfProgram[] = $dimension['id_dimension_type'];
			    	}

			    }else{
			    	// echo "<tr>";
			    	// 	echo "<td colspan=2>";
			    			echo "<div class='callout callout-danger'>";
			    			echo "<h4>Não há dimensões cadastradas para esse programa.</h4>";
			    			echo "</div>";
			    	// 	echo "</td>";
			    	// echo "</tr>";
			    }
		    ?>
		</div>
		<div class="col-lg-6">
			<h3><b>Dimensões cadastradas:</b>
			<br><small>Adicionar dimensões ao programa:</small></h3>
			<br>
			<?php
				if($allDimensionsTypes !== FALSE){

					foreach($allDimensionsTypes as $dimension){
	    				
	    				$dimensionIsOnProgram = array_search($dimension['id_dimension_type'], $dimensionsOfProgram);

	    				if($dimensionIsOnProgram === FALSE){

		    				echo anchor(
		    					"coordinator/addDimensionToEvaluation/{$programData['id_program']}/{$programEvaluation['id_program_evaluation']}/{$dimension['id_dimension_type']}",
		    					"<i class='fa fa-plus'></i> Adicionar <b><i>".$dimension['dimension_type_name']."</i></b>",
		    					"class='btn btn-primary btn-flat'"
		    				);
		    				echo "<br>";
		    				echo "<br>";
	    				}else{
	    					echo anchor(
		    					"",
		    					"Dimensão <b><i>".$dimension['dimension_type_name']."</i></b> já adicionada",
		    					"class='btn btn-danger btn-flat' disabled = 'true'"
		    				);
		    				echo "<br>";
		    				echo "<br>";
	    				}
			    	}
				}else{
					echo "<div class='callout callout-danger'>";
	    			echo "<h4>Não há nenhuma dimensão cadastrada.</h4>";
	    			echo "</div>";
				}
			?>
		</div>
    </div>
		<!-- </tbody>
	</table>
</div> -->


<br>
<?php echo anchor('coordinator/coordinator_programs', "Voltar", "class='btn btn-danger'");?>