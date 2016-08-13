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
<h4><b>Necessita de aprovação do orientador?</b><?= $needsMastermindApproval ?></h4>
<?php 
	$status = $offerData['offer_status']; 
	$startDate = convertDateTimeToDateBR($offerData['start_date']);
	$endDate = convertDateTimeToDateBR($offerData['end_date']);
	$reload = TRUE;
	$offerIdHidden = array(
		'id' => "offer_id",
		'type' => "hidden",
		'value' => $idOffer
	);
	if($status == OfferConstants::APPROVED_OFFER){
		echo "<h4><b>Período de matrículas </b>:".$offerData['enrollment_period']."</h4>";
		echo "<button data-toggle='modal' data-target='#form_enrollment_period' class='btn btn-primary'>";
		echo "<i class='fa fa-edit'> Editar período</i>";
		echo "</button>";
		$now = new Datetime();
		$now = $now->format("d/m/Y");
		if(empty($endDate) || $now <= $endDate){
			echo anchor("secretary/offer/finishEnrollmentPeriod/{$idOffer}/{$course['id_course']}", "<i class='fa fa-times-circle'> Encerrar período</i>", "class='btn btn-danger'");
		}
	}
?>
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
	
	<div class="col-lg-3">
        <?php echo anchor("offer_list", "Voltar", "id='back_btn_offer_list' class='btn btn-danger'"); ?>
	</div>

	<div class="col-xs-9">
		<?php
	
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
						<?php $reload = FALSE; ?>
						<button id="define_period_btn" data-toggle="modal" data-target="#form_enrollment_period" class="btn bg-olive" >
							Definir período
						</button>	
						<br>
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

<!-- Modal HTML -->
<div id="form_enrollment_period" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
            	Período de matrículas
        </div>
       	<div class="alert alert-info alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <i class='fa fa-info'></i>
            <p class="text">Após o período definido os alunos não poderão solicitar mais disciplinas e nem alterar suas solicitações. Portanto, esse período inclui o período para matrícula e ajuste.</p>
        </div>
        <div class="modal-body">
		    <form id='form'> 

		       <?php 
		       formToEnrollmentPeriod($startDate, $endDate, $idOffer, $reload); ?>
	       </form>
		    <br>
		    <br>
			<medium><p class="text-warning">Não se esqueça de clicar em salvar.</p></medium>
		</div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="new_enrollment_period">Salvar</button>
			<?= form_close() ?>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
            <div id="alert-msg">
        </div>
    </div>
</div>
</div>
</div>