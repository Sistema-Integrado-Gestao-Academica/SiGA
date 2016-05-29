<?php require_once(MODULESPATH."/secretary/constants/DocumentConstants.php"); ?>

<h2 class="principal">Solicitação de documentos para o curso <i><b><?php echo $courseData['course_name']?></b></i></h2>

<h3><i class="fa fa-list"></i> Documentos solicitados pelos alunos:</h3>
<?php 
	echo anchor(
		"secretary/documentrequest/displayAnsweredRequests/{$courseData['id_course']}",
		"Solicitações atendidas",
		"class='btn btn-success'"
	); 
?>
<?php if($courseRequests !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Aluno</th>
			        <th class="text-center">Matrícula</th>
			        <th class="text-center">Tipo do documento</th>
			        <th class="text-center">Data da socilicitação</th>
			        <th class="text-center">Status</th>
			        <th class="text-center">Dados adicionais</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($courseRequests as $request){

						echo "<tr>";
				    		echo "<td>";
				    		echo $request['id_request'];
				    		echo "</td>";

				    		echo "<td>";
							
							echo $user[$request['id_request']]['name']; 

				    		echo "</td>";

				    		echo "<td>";
				    			echo $request['id_student'];
				    		echo "</td>";

				    		echo "<td>";
					    		$docConstants = new DocumentConstants();
					    		$allTypes = $docConstants->getAllTypes();
					    		
					    		if($allTypes !== FALSE){
					    			echo $allTypes[$request['document_type']];
					    		}else{
					    			echo "-";
					    		}
				    		echo "</td>";

				    		echo "<td>";
				    			echo $request['date'];
				    		echo "</td>";

				    		echo "<td>";
				    			$status = $request['status'];
				    			echo prettyDocStatus($status);
				    		echo "</td>";

				    		echo "<td>";
				    			$type = $request['document_type'];
				    			$docName = $request['other_name'];
				    			echo prettyDocType($type, $docName);
				    		echo "</td>";
				    		
				    		echo "<td>";
				    		echo "<div class='callout callout-info'>";

				    		if($request['status'] === DocumentConstants::REQUEST_READY){
				    			echo "<h4>Este documento já está disponível para o aluno.</h4>";
				    		}else{	
					    		echo anchor(
					    			"secretary/documentrequest/documentReady/{$request['id_request']}/{$courseData['id_course']}",
						    		"<i class='fa fa-check'></i> Expedir documento",
						    		"class='btn btn-success'"
					    		);
					    		echo "<p>Permite que o aluno saiba que o documento está pronto.</p>";
				    		}
				    		echo "</div>";
				    		echo "</td>";
			    		echo "</tr>";
			    	}
?>			    
			</tbody>
		</table>
		</div>

<?php
 	} else{
?>
	<div class="callout callout-info">
		<h4>Nenhum solicitação de documentos feita para o curso.</h4>
	</div>
<?php }?>

<?= anchor('documents_report', 'Voltar', "class='btn btn-danger'")?>
