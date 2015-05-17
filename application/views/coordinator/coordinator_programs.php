<br>
<br>

<h2 align="center">Programas para o coordenador <b><i><?php echo $userData['name']; ?></i></b></h2>
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
				    		echo anchor("coordinator/program_evaluation_index/{$program['id_program']}","<i class='fa fa-certificate'></i> Avaliação do Programa", "class='btn btn-primary'");
				    		echo "</td>";
			    		echo "</tr>";	
			    	}
		    	}else{
		    		echo "<tr>";
		    			echo "<td colspan=4>";
		    			echo "<div class='callout callout-info'>";
		    			echo "<h4>Não há programas cadastrados para o coordenador <b>".$userData['name']."</b>.</h4>";
		    			echo "</div>";
		    			echo "</td>";
		    		echo "</tr>";
		    	}
	    	?>
		    
		</tbody>
	</table>
</div>