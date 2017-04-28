<script src=<?=base_url("js/selective_process_evaluation.js")?>></script>
<h2 class="principal">Avaliação de Candidatos<b></b>
	<br>
	<small>Fase atual: <b><?= $phasesNames[$currentPhaseProcessId]->phase_name ?></b></small>
</h2>

<div class="row">
<div class="col-md-10 col-md-offset-1">
  <?php
    alert(function(){
      echo '<p>Você só poderá salvar a nota do candidato para a fase atual.</p>';
    });
  ?>
</div>

<br>
<br>
<?php if ($candidates){ 
	foreach ($candidates as $candidateId => $candidate) {
		 ?>
	<div class="col-lg-10 col-lg-offset-1">
		<!-- Primary box -->
		<div class="box box-primary">
		  <div class="box-header">
			  <h3 class="box-title">Candidato: <b><?= $candidateId?></b></h3>
			  <div class="box-tools pull-right">
				  <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
			  </div>
		  </div>
			<div class="box-body">
	  <?php 
		foreach ($candidate as $processPhase => $phaseEvaluations) :
			$idSubscription = $phaseEvaluations['evaluations'][0]['id_subscription']; 
			$idForLabel = $processPhase."_".$idSubscription."_label";
			$phaseName = $phasesNames[$processPhase]->phase_name;?>			
			<div class="row">
				<div class="col-lg-6">
					<h4>Fase: <b> <?=$phaseName ?></b> </h4>
				</div> 
				<div class="col-lg-6">
					<h4 id=<?=$idForLabel?>>Resultado: <b><?= $phaseEvaluations['phase_result'] ?></b></h4>
				</div>
			</div>
			<div class="row">
				<?php showEvaluations($phaseEvaluations['evaluations'], $teacherId, $phaseName, $currentPhaseProcessId);?>
			</div> <!-- /. row -->
		<?php endforeach ?>
			</div> <!-- /. box-body -->
		</div><!-- /.box -->
	</div>
<?php 
	} 
} ?>
</div>
<?php echo anchor("selection_process_evaluation", "Voltar", "class='btn btn-danger pull-left'"); ?>

<?php
function showEvaluations($phaseEvaluations, $teacherId, $phaseName, $currentPhaseProcessId){

	if($phaseEvaluations){
	  foreach ($phaseEvaluations as $evaluation) {
	  	echo "<div class='col-lg-6'>";
				showForm($evaluation, $teacherId, $currentPhaseProcessId);
			echo "</div>";
	  }
	}
}	

function showForm($evaluation, $teacherId, $currentPhaseProcessId){
	
  $fieldId = $evaluation['id_teacher']."_".$evaluation['id_subscription'].'_'.$evaluation['id_process_phase'];
  
  $gradeInput = array(
		"id" => "candidate_grade_".$fieldId,
		"name" => "candidate_grade_".$fieldId,
		"type" => "number",
		"min" => 0,
		"max" => 100,
		"steps" => 1,
		"class" => "form-control",
		"value" => $evaluation['grade'],
  );

  
  $submitBtn = FALSE;
	$isTeacherEvaluation = $teacherId == $evaluation['id_teacher'];
	$label = $isTeacherEvaluation ? "Sua nota": "Nota do outro avaliador";

	echo form_label($label, "grade_label");
	if($isTeacherEvaluation){
		$ids = $teacherId.','.$evaluation['id_subscription'].','.$evaluation['id_process_phase'];
		$submitBtn = $currentPhaseProcessId == $evaluation['id_process_phase'] 
								? "<button type='button' onclick='saveCandidateGrade({$ids})' class='btn btn-primary'> Salvar</button>"
								: "<button type='button' class='btn btn-primary disabled'> Salvar</button>";
		echo "<div class='input-group'>";
			echo form_input($gradeInput);
		    echo "<span class='input-group-btn'>{$submitBtn}</span>";
		echo "</div>";
	}
	else{
		$gradeInput['disabled'] = TRUE;
		echo form_input($gradeInput);
	}

	echo "<div id=status_{$fieldId}></div>";
}

?>




 