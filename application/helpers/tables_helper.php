<?php

function courseTableToSecretaryPage($courses, $masterDegrees, $doctorates){
	echo "<div class=\"box-body table-responsive no-padding\">";
	echo "<table class=\"table table-bordered table-hover\">";
		echo "<tbody>";
		    echo "<tr>";
		        echo "<th class=\"text-center\">ID</th>";
		        echo "<th class=\"text-center\">Curso</th>";
		        echo "<th class=\"text-center\">Tipo</th>";
		        echo "<th class=\"text-center\">Ações</th>";
		    echo "</tr>";

		    	foreach($courses as $courseData){
		    		$courseType = $courseData['course_type'];
		    		$courseId = $courseData['id_course'];

		    		switch($courseType){
		    			case "academic_program":

		    				$thereIsMasterDegree = array_key_exists($courseId, $masterDegrees);
		    				$thereIsDoctorate = array_key_exists($courseId, $doctorates);
		    				
		    				if($thereIsMasterDegree){

		    					if($thereIsDoctorate){

		    						echo "<tr>";
							    		echo "<td align='center' rowspan=\"3\">";
							    		echo $courseId;
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-primary\">Programa Acadêmico</span>";
							    		echo "</td>";

							    		echo "<td>";
							    		echo anchor('',"<i class='fa fa-plus-square'>Matricular Aluno</i>");
							    		echo "</td>";
						    		
						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-success\">Mestrado Acadêmico</span>";
							    		echo "</td>";
						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$doctorates[$courseId]['doctorate_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-warning\">Doutorado Acadêmico</span>";
							    		echo "</td>";
						    		echo "</tr>";

		    					}else{

				    				echo "<tr>";
							    		echo "<td align='center' rowspan=\"2\">";
							    		echo $courseId;
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-primary\">Programa Acadêmico</span>";
							    		echo "</td>";
						    		
						    		echo "</tr>";

						    		echo "<tr>";
							    		echo "<td>";
							    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
							    		echo "</td>";

							    		echo "<td>";
							    		echo "<span class=\"label label-success\">Mestrado Acadêmico</span>";
							    		echo "</td>";
						    		echo "</tr>";

		    					}
		    				}

		    				break;

		    			case "professional_program":
		    				$thereIsMasterDegree = array_key_exists($courseId, $masterDegrees);
		
							if($thereIsMasterDegree){

			    				echo "<tr>";
						    		echo "<td align='center' rowspan=\"2\">";
						    		echo $courseId;
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<i class='fa fa-hand-o-down'></i>	".$courseData['course_name'];
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<span class=\"label label-primary\">Programa Profissional</span>";
						    		echo "</td>";
					    		
					    		echo "</tr>";

					    		echo "<tr>";
						    		echo "<td>";
						    		echo "<i class='fa fa-caret-right'></i>	".$masterDegrees[$courseId]['master_degree_name'];
						    		echo "</td>";

						    		echo "<td>";
						    		echo "<span class=\"label label-success\">Mestrado Profissional</span>";
						    		echo "</td>";
					    		echo "</tr>";
							}

		    				break;

		    			default:
				    		echo "<tr>";
				    		echo "<td>";
				    		echo $courseId;
				    		echo "</td>";

				    		echo "<td>";
				    		echo $courseData['course_name'];
				    		echo "</td>";

				    		echo "<td>";
				    		if($courseType == "graduation"){
							    echo "<span class=\"label label-primary\">Graduação</span>";
				    		}else if($courseType == "ead"){
							   	echo "<span class=\"label label-primary\">EAD</span>";
				    		}else{
				    			echo "-";
				    		}
				    		echo "</td>";
				    		echo "</tr>";
		    				break;
		    		}
		    	}
		    
		echo "</tbody>";
	echo "</table>";
echo "</div>";

}