<br>	
<?php

require_once(MODULESPATH."/secretary/constants/EnrollmentConstants.php");
$button = array(
			"class" => "btn bg-olive btn-block",
			"content" => "Solicitar inscrição",
			"type" => "submit"
		);

	if($courseGuest !== FALSE){ 
	
		$choosenCourseId = $courseGuest[0]['id_course'];
		$courseName = $coursesName[$choosenCourseId];
	?>
		<h4>Olá, <b><?=$user->getName();?> </b>
		<br><br>
		<div class="panel panel-success" id="course_guest_panel">
		  <div class="panel-body" id="course_guest_title">
			<b>Curso solicitado:</b> <?= $courseName ?> 
		  </div>
		  <div class="panel-footer">
			<b> Status da solicitação:</b>
			<?php		  	
			switch ($courseGuest[0]['status']) {
		
				case EnrollmentConstants::CANDIDATE_STATUS:
					echo "<span class='label label-info'>Aberta</span>";
					$button['disabled'] = TRUE;
					break;
				
				case EnrollmentConstants::UNKNOWN_STATUS:
					echo "<span class='label label-danger'>Recusada</span>";
					$button['content'] = "Solicitar inscrição novamente";
					include '_solicit_inscription.php';
					break;
			} ?>
		  </div>
		</div>
<?php } 
	
	else{

		include '_solicit_inscription.php';
	}
	?>

