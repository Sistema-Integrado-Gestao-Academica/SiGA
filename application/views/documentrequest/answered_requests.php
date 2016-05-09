<?php require_once(APPPATH."/constants/DocumentConstants.php"); ?>

<h2 class="principal">Solicitação de Documentos atentidas</h2>

<h3><i class="fa fa-archive"></i> Solicitações atentidas:</h3>

<?php if($answeredRequests !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Aluno</th>
			        <th class="text-center">Matrícula</th>
			        <th class="text-center">Tipo do documento</th>
			        <th class="text-center">Data da solicitação</th>
			        <th class="text-center">Status</th>
			        <th class="text-center">Dados adicionais</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
			    	foreach($answeredRequests as $request){

						echo "<tr>";
				    		echo "<td>";
				    		echo $request['id_request'];
				    		echo "</td>";

				    		echo "<td>";
				    			$studentId = $request['id_student'];
				    			$user = new Usuario();
				    			$userData = $user->getUserById($studentId);
				    			echo $userData['name'];
				    		echo "</td>";
				    		
				    		echo "<td>";
				    			echo $studentId;
				    		echo "</td>";

				    		echo "<td>";
					    		$docConstants = new DocumentConstants();
					    		$allTypes = $docConstants->getAllTypes();
					    		echo $allTypes[$request['document_type']];
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
				    				echo "<b>Documento solicitado: </b>".$request['other_name'];
				    				break;
				    			
				    			default:
				    				echo "-";
				    				break;
				    		}
				    		echo "</td>";

				    		echo "<td>";
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
		<h4>Nenhuma solicitação de documentos atendida.</h4>
	</div>
<?php }?>

<?= anchor("documentrequest/documentRequestReport/{$courseId}", 'Voltar', "class='btn btn-danger'")?>
