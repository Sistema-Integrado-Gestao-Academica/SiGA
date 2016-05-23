<br>
<br>

<?php 
	echo "<div class=\"col-lg-12 col-xs-6\">";
	echo "<div class='panel panel-primary'>";
	echo "<div class='panel-heading'><h4>Relação de secretários do curso: ". $course['course_name'] ." </h4></div>";
	echo "<div class='panel-body'>";
	echo "<div class=\"modal-info\">";
	echo "<div class=\"modal-content\">";

	if($secretaries !== FALSE){
		foreach ($secretaries as $key => $secretary){
			$userData = new UserController();
			$secretaryData = $userData->getUserById($secretary['id_user']);
			$secretaryGroup = $userData->getUserGroupNameByIdGroup($secretary['id_group']);
			echo "<div class=\"modal-header bg-news\">";
				echo "<h4 class=\"model-title\"> Secretário : ". ucfirst($secretaryData['name']) ."</h4>";
			echo "</div>";
			echo "<div class=\"modal-body\">";
				echo "<h4>";
					switch ($secretaryGroup) {
						case GroupConstants::ACADEMIC_SECRETARY_GROUP:
							echo "Secretaria acadêmica";
							break;
						case GroupConstants::FINANCIAL_SECRETARY_GROUP:
							echo "Secretaria financeira";
							break;
						default:
							break;
					}
				echo "</h4>";
			echo "</div>";

		}
	}else{
		callout("info", "Nenhum secretário para este curso.");
	}

					echo "</div>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";

?>