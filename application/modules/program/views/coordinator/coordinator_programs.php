<br>
<br>

<h2 align="center">Programas para o coordenador <b><i><?php echo $userData->getName(); ?></i></b></h2>
<br>

<div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<tbody>
		    <tr>
		        <th class="text-center">Código</th>
		        <th class="text-center">Programa</th>
		        <th class="text-center">Ano de abertura</th>
		        <th class="text-center">Ações</th>
		    </tr>

		    <?php

		    	if($coordinatorPrograms !== FALSE){
		    	
			    	foreach($coordinatorPrograms as $program){

			    		$programEvaluations = $programObject->getProgramEvaluations($program['id_program']);

			    		$evaluationsPeriods = array();
						if($programEvaluations !== FALSE){

							foreach($programEvaluations as $evaluation){
								$evaluationsPeriods[$evaluation['id_program_evaluation']] = "Período ".$evaluation['start_year']." - ".$evaluation['end_year'];
							}
						}else{
							$evaluationsPeriods = array(0 => 'Nenhuma avaliação para este programa.');
						}

						echo "<tr>";
				    		echo "<td>";
				    		echo $program['id_program'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $program['acronym']." - ".$program['program_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $program['opening_year'];
				    		echo "</td>";

				    		echo "<td>";

				    			if($programEvaluations !== FALSE){

						    		echo "<div class='dropdown'>";
						    		echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
			                          <i class='fa fa-certificate'></i> Avaliação do Programa <span class='fa fa-caret-down'></span></button>";

			                           echo "<ul class='dropdown-menu'>";
			                           	 
			                        	foreach($evaluationsPeriods as $evaluationId => $period){
			                        		echo "<li>";
							    				echo anchor(
							    					"program/coordinator/program_evaluation_index/{$program['id_program']}/{$evaluationId}",
							    					$period
							    				);
			                        		echo "</li>";
			                        	}
			                           	
			                           echo "</ul>";
		                            echo "</div>";
				    			}else{
				    				echo "<div class='callout callout-info'>";
				    					echo "<h4>Nenhuma avaliação para o programa.</h4>";
				    					echo anchor("program/coordinator/createProgramEvaluation/{$program['id_program']}", "Criar Avaliação", "class='btn btn-primary btn-flat'");
				    				echo "</div>";
				    			}
				    			echo anchor("program/coordinator/updateProgramArea/{$program['id_program']}", "Atualizar área do programa", "class='btn btn-primary btn-flat'");
				    		echo "</td>";
			    		echo "</tr>";	
			    	}
		    	}else{
		    		echo "<tr>";
		    			echo "<td colspan=4>";
		    			echo "<div class='callout callout-info'>";
		    			$userName = $userData->getName();
		    			echo "<h4>Não há programas cadastrados para o coordenador <b>".$userName."</b>.</h4>";
		    			echo "</div>";
		    			echo "</td>";
		    		echo "</tr>";
		    	}
	    	?>
		    
		</tbody>
	</table>
</div>

<br>
<?php echo anchor('coordinator_home', "Voltar", "class='btn btn-danger'");?>