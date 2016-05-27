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
				    		switch($request['status']){
				    			case DocumentConstants::REQUEST_OPEN:
				    				echo "<span class='label label-info'>Aberta</span>";
				    				break;
				    			case DocumentConstants::REQUEST_READY:
				    				echo "<span class='label label-success'>Pronto</span>";
				    				break;
				    			default:
				    				echo "-";
				    				break;
				    		}
				    		echo "</td>";

				    		echo "<td>";
				    		switch($request['document_type']){
				    			case DocumentConstants::OTHER_DOCS:
				    				echo "<b>Solicitação: </b>".$request['other_name'];
				    				break;
				    			
				    			default:
				    				echo "-";
				    				break;
				    		}
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
					    		echo "<p>Permite que o aluno saiba que o documento está pronto para buscar.</p>";

					    		echo "<b>ou</b><br><br>";

					    		echo anchor(
				    				"#upload_doc_".$request['id_request'],
				    				"<i class='fa fa-globe'></i> Disponibilizar online",
									"class='btn-md'
				    				data-toggle='collapse'
				    				aria-expanded='false'
				    				aria-controls='upload_doc'"
				    			);
					    		echo "<p>Disponibiliza uma versão digital do documento.</p>";
				    		}

				    		echo "<div class='collapse' id='upload_doc_".$request['id_request']."'>";
				    			echo form_open_multipart("provide_doc_online");

								// $hidden = array(
								// );

								// echo form_hidden($hidden);

								$noticeFile = array(
									"name" => "requested_doc",
									"id" => "requested_doc",
									"type" => "file"
								);
								
								$submitFileBtn = array(
									"id" => "provide_online_btn",
									"class" => "btn btn-info btn-flat",
									"content" => "<i class='fa fa-globe'></i> Expedir online",
									"type" => "submit"
									// "style" => "margin-top: 5%;"
								);

								echo form_label("Enviar documento <small><i>(Arquivos '.pdf', '.png' e '.jpg' apenas)</i></small>:", "requested_doc");

								echo form_input($noticeFile);
								echo "<br>";

								echo form_button($submitFileBtn);

								echo form_close();
				    		echo "</div>";
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
