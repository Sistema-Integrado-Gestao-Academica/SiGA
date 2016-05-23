<br>
<h2 align="center">Dimensões de avaliação</h2>
<br>

<div class="box-body table-responsive no-padding">
	<table class="table table-bordered table-hover">
		<tbody>
		    <tr>
		        <th class="text-center">Código</th>
		        <th class="text-center">Dimensão</th>
		        <th class="text-center">Peso padrão</th>
		        <th class="text-center">Ações</th>
		    </tr>

		    <?php

		    	if($allDimensions !== FALSE){
		    		
		    		$quantityOfDimensions = 0;
		    		$dimensionsWeight = 0;
			    	foreach($allDimensions as $dimension){

						echo "<tr>";
				    		echo "<td>";
				    		echo $dimension['id_dimension_type'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $dimension['dimension_type_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		echo $dimension['default_weight']."%";
				    		echo "</td>";

				    		echo "<td>";
				    		
				    		echo "</td>";
			    		echo "</tr>";

			    		$quantityOfDimensions += 1;
			    		$dimensionsWeight += $dimension['default_weight'];
			    	}

			    	echo "<tr>";
			    		echo "<td colspan='4'>";
				    	echo "<div class='callout callout-info'>";
		    			echo anchor("#create_dimension",
		    				"Nova dimensão de avaliação",
		    				"class='btn btn-primary'
		    				data-toggle='collapse'
							aria-expanded='false'
							aria-controls='create_dimension'"
		    			);

		    			$dimension_name = array(
							'name' => 'new_dimension_name',
							'id' => 'new_dimension_name',
							"class" => "form-control",
							"type" => "text",
							"required" => TRUE,
							"maxlength" => "30"
						);

						$weight = array(
							'name' => 'dimension_weight',
							'id' => 'dimension_weight',
							"class" => "form-control",
							"type" => "number",
							"required" => TRUE,
							"min" => 0,
							"step" => 0.01
						);

		    			$submitBtn = array(
							"class" => "btn bg-olive btn-flat",
							"type" => "submit",
							"content" => "Criar dimensão"
						);

		    			define("MAX_WEIGHT", 100);
		    			if($dimensionsWeight == MAX_WEIGHT){

		    				$maxWeight = 0;
		    				$weight['value'] = 0;
							$message = "Todas as outras dimensões já utilizam 100% no total. O valor do peso será <b>0%</b>.";
						}else{
							$maxWeight = MAX_WEIGHT - $dimensionsWeight;
							$message = "O peso máximo possível é <b>".$maxWeight."%</b>";
						}
						
						$weight['max'] = $maxWeight;


		    			echo "<div class='collapse' id='create_dimension'>";
		    				echo "<div class='row'>";
		    				echo form_open("coordinator/createDimension");
								echo "<br>";
								echo "<h4>".$message."</h4>";
								echo "<br>";

								echo "<div class='col-lg-6'>";
								echo form_label("Informe o nome da dimensão:","new_dimension_name");
								echo form_input($dimension_name);
		    					echo "</div>";

								echo "<div class='col-lg-4'>";
								echo form_label("Informe o peso padrão:","dimension_weight");
								echo form_input($weight);
		    					echo "</div>";

		    					echo "<br>";
								echo form_button($submitBtn);
		    				echo form_close();
		    				echo "</div>";
		    			echo "</div>";

		    			echo "</div>";
			    		echo "</td>";
			    	echo "</tr>";
		    	}else{
		    		echo "<tr>";
		    			echo "<td colspan=4>";
		    			echo "<div class='callout callout-warning'>";
		    			echo "<h4>Não há dimensões cadastradas.</h4>";
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