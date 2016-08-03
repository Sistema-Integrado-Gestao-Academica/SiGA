<script src=<?=base_url("js/offer.js")?>></script>

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

	$offerIdHidden = array(
		'id' => "offer_id",
		'type' => "hidden",
		'value' => $idOffer
	);

?>
<div class="row">
	
	<div class="col-lg-3">
        <?php echo anchor("offer_list", "Voltar", "id='back_btn_offer_list' class='btn btn-danger'"); ?>
	</div>

	<div class="col-xs-9">
		<?php
		
		$status = $offerData['offer_status'];
		$startDate = convertDateTimeToDateBR($offerData['start_date']);
		$endDate = convertDateTimeToDateBR($offerData['end_date']);
		if($status === OfferConstants::PROPOSED_OFFER){ 

			if($allDisciplines !== FALSE){ ?>

				<button data-toggle="collapse" data-target=<?="#confirmation"?> class="btn btn-primary" >
					Aprovar lista de oferta 
				</button>
				
				<div id=<?="confirmation"?> class="collapse">
					<br>
		    		<?php callout("info", "Você pode definir um período de matrículas. <br>
		    			Se você optar por não definir o período, a matrícula começará com a aprovação da lista e você poderá encerrá-la a qualquer momento.");?>
					<br>
					<div class="row">
					<div class="col-lg-6">
						<h2> Período de matrículas </h2>
						<div id="alert-msg"> 
						</div>
						<div class="form-group">
							<?= form_label("Data de Início", "enrollment_start_date") ?>
							<?= form_input(array(
								"name" => "enrollment_start_date",
								"id" => "enrollment_start_date",
								"type" => "text",
								"class" => "form-control",
								"value" => $startDate
							)) ?>
							<?= form_error("enrollment_start_date"); ?>
						</div>
						<div class="form-group">
							<?= form_label("Data de Fim", "enrollment_end_date") ?>
							<?= form_input(array(
								"name" => "enrollment_end_date",
								"id" => "enrollment_end_date",
								"type" => "text",
								"class" => "form-control",
								"value" => $endDate
							)) ?>
							<?= form_error("enrollment_end_date"); ?>
						</div>
						<?= form_input($offerIdHidden); ?>
				        <div class="footer">
				            <?= form_button(array(
				                "id" => "new_enrollment_period",
				                "class" => "btn bg-olive btn-block",
				                "content" => "Definir período",
				                "type" => "submit"
				            )) ?>
				        </div>
						<?= form_close() ?>
					<br>
					</div>
					<div class="col-lg-3">
					<?php
					echo anchor("secretary/offer/approveOfferList/{$idOffer}", "Aprovar Oferta", "id='approve_offer_list_btn' class='btn btn-primary'"); ?>
					</div>
					<br><br>
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

</div>

