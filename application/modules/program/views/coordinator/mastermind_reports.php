<br>
<br>

<?php 
	echo "<div class=\"col-lg-12 col-xs-6\">";
		echo "<div class=\"modal-info\">";
			echo "<div class=\"modal-content\">";
				echo "<div class=\"modal-header bg-news\">";
					echo "<h4 class=\"model-title\">Total de Professores do Curso: </h4>";
				echo "</div>";
				echo "<div class=\"modal-body\">";
					echo "<h4>";
						echo "Existem no momento " . sizeof($totalMasterminds) . " professores cadastrados para este curso.";
					echo "</h4>";
				echo "</div>";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	showMastermindsStudents($totalMasterminds);

?>