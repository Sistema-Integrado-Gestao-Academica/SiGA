<h2 class='principal'>Lista de Oferta</h2>
<h3><b>Curso</b>: <?= $course['course_name'] ?></h3>

<?php
$idOffer = $offerData['id_offer'];

if($offerData['needs_mastermind_approval'] === EnrollmentConstants::NEEDS_MASTERMIND_APPROVAL){
	$needsMastermindApproval = "Sim.";
}
else{
	$needsMastermindApproval = "Não.";
} ?>
<h4><b>Necessita de aprovação do orientador?</b><?= $needsMastermindApproval ?></h3>

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

    }
    else{

    	echo "<tr>";
    	echo "<td colspan=5>";
    		callout("warning", "Não há disciplinas cadastradas no currículo deste curso no momento.");
    	echo "</td>";
		echo "</tr>";
    }

	buildTableEndDeclaration();

?>
<div class="row">
	<div class="col-xs-9">
		<?php
		
		$status = $offerData['offer_status'];
		if($status === OfferConstants::PROPOSED_OFFER){ 

			if($allDisciplines !== FALSE){ ?>

				<button data-toggle="collapse" data-target=<?="#confirmation"?> class="btn btn-primary" >
					Aprovar lista de oferta 
				</button>
				
				<div id=<?="confirmation"?> class="collapse">
					<br>
		    		<?php callout("warning", "Ao aprovar a lista de oferta não é possível adicionar ou retirar disciplinas. Deseja aprovar?");?>
					<br>
					<?php
					echo anchor("secretary/offer/approveOfferList/{$idOffer}", "Aprovar", "id='approve_offer_list_btn' class='btn btn-primary'");
			        ?>
				</div>
			<?php
			}
			else{
				echo anchor("", "Aprovar lista de oferta", "id='approve_offer_list_btn' class='btn btn-primary' data-container=\"body\"
		             data-toggle=\"popover\" data-placement=\"top\" data-trigger=\"hover\" disabled='true'
		             data-content=\"Não é possível aprovar uma lista sem disciplinas.\"");
			}
		}
		
		?>
	</div>
	
	<div class="col-xs-3">
		<?php echo anchor("offer_list", "Voltar", "class='btn btn-danger'"); ?>
	</div>
</div>

