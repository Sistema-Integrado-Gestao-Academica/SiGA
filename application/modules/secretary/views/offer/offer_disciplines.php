<br>
<h3>Adicionar disciplinas a lista de oferta</h3>
<br>
<h4>Lista de disciplinas</h4>

<?php 
	buildTableDeclaration();

	buildTableHeaders(array(
		'Código',
		'Sigla',
		'Disciplina',
		'Créditos',
		'Ações'
	));

    if($allDisciplines !== FALSE){

	    foreach($allDisciplines as $discipline){

		    echo "<tr>";
		    	echo "<td>";
	    			echo $discipline['discipline_code'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['name_abbreviation'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['discipline_name'];
		    	echo "</td>";

		    	echo "<td>";
		    		echo $discipline['credits'];
		    	echo "</td>";

		    	echo "<td>";
					echo anchor("secretary/offer/displayDisciplineClasses/{$discipline['discipline_code']}/{$idOffer}/{$course['id_course']}", "<i class='fa fa-tasks'></i> Gerenciar turmas para a oferta", "class='btn btn-primary'");
		    	echo "</td>";
		    echo "</tr>";
	    }

    }else{

    	echo "<tr>";
    	echo "<td colspan=5>";
    		callout("warning", "Não há disciplinas cadastradas no currículo deste curso no momento.");
    	echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();

	echo "<br>";
	echo anchor("secretary/offer/displayDisciplines/{$idOffer}/{$course['id_course']}", "Voltar", "class='btn btn-danger'");
?>
