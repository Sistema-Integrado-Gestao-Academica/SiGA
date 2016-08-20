<h2 class="principal">Relatório Geral de Matrícula</h2>

<h4>Programas para o(a) secretário(a) <b><?php echo $userName?></b>:</h4>
<?php

    if($programs !== FALSE){
        buildTableDeclaration();
        
        buildTableHeaders(array(
			'Programa',
			'Ações'
		));

        foreach ($programs['programs'] as $program) {
			
			echo "<tr>";
    
			echo "<td>";
				echo $program['acronym']." - ".$program['program_name'];
			echo "</td>";

			echo "<td>";
				$programId = $program['id_program'];
			 	echo anchor("secretary/enrollment/programEnrollmentReport/{$programId}","<i class='fa fa-plus-square'> Relatório Geral de Matrícula</i>", "class='btn btn-primary'");
			echo "</td>";

			echo "</tr>";
        }



		buildTableEndDeclaration();
    }
    else{
?>
    <div class="callout callout-info">
        <h4>Nenhum programa cadastrado no momento para sua secretaria.</h4>
    </div>
<?php }

    echo anchor("secretary_home", "Voltar", "class='btn btn-danger'");
?>