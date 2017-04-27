<h2 class="principal">Candidatos do processo seletivo</h2>
<script src=<?=base_url("js/selective_process_evaluation.js")?>></script>

<div id="save_candidate_grade_status"></div>
<?php if ($candidates){ 
	foreach ($candidates as $candidateId => $candidate) {
		 ?>
		<!-- Primary box -->
		<div class="box box-primary">
		  <div class="box-header">
			  <h3 class="box-title">Candidato: <b><?= $candidateId?></b></h3>
			  <div class="box-tools pull-right">
				  <button class="btn btn-primary btn-xs" data-widget="collapse"><i class="fa fa-minus"></i></button>
			  </div>
		  </div>
		<div class="box-body row">
	  <?php 
		$evaluations = count($candidate);
		for ($key = 0; $key < $evaluations; $key+=2) {
		$idProcessPhase = $candidate[$key]['id_process_phase'];
		$phaseName = $phasesNames[$idProcessPhase]->phase_name; ?>
		<div class="col-lg-4">
			Avaliação: <b> <?=$phaseName ?></b>
			<?php showEvaluations($candidate[$key], $candidate[$key + 1], $teacherId, $phaseName, $currentPhaseProcessId);?>
		</div>

		<?php 
		} ?>
			</div> <!-- /. box-body row -->
		</div><!-- /.box -->
<?php 
	} 
}

function showEvaluations($evaluationFisrtTeacher, $evaluationSecondTeacher, $teacherId, $phaseName, $currentPhaseProcessId){
	
  echo "<br><br>";
	showForm($evaluationFisrtTeacher, $teacherId, $currentPhaseProcessId);
  echo "<hr>";
  showForm($evaluationSecondTeacher, $teacherId, $currentPhaseProcessId);
}	

function showForm($evaluation, $teacherId, $currentPhaseProcessId){
	
  $id = "candidate_grade_".$evaluation['id_teacher']."_".$evaluation['id_subscription'].'_'.$evaluation['id_process_phase'];
  
  $gradeInput = array(
		"id" => $id,
		"name" => $id,
		"type" => "number",
		"min" => 0,
		"max" => 100,
		"steps" => 1,
		"class" => "form-control",
		"value" => $evaluation['grade'],
  );

  
  $submitBtn = FALSE;
	$isTeacherEvaluation = $teacherId == $evaluation['id_teacher'];
	$label = "Nota do outro avaliador";

  if($isTeacherEvaluation){
  	$label = "Sua nota";
		$ids = $teacherId.','.$evaluation['id_subscription'].','.$evaluation['id_process_phase'];
		$submitBtn = $currentPhaseProcessId == $evaluation['id_process_phase'] 
								? "<button type='button' onclick='saveCandidateGrade({$ids})' class='btn btn-primary'> Salvar</button>"
								: FALSE;
  }
  else{
  	$gradeInput['disabled'] = TRUE;
  }

  echo form_label($label, "grade_label");
  echo "<div class='row'>";
	  echo "<div class='col-lg-9'>";
	  	echo form_input($gradeInput);
	  echo "</div>";
	  if($submitBtn){
	  	echo "<div class='col-lg-2'>";
				echo $submitBtn;
	  	echo "</div>";
	  }
	echo "</div>";

}

?>




 