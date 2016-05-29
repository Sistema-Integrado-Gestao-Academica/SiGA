<?php require_once(MODULESPATH."/secretary/constants/DocumentConstants.php"); ?>

<h2 class="principal">Solicitação de Documentos arquivadas</h2>

<h3><i class="fa fa-archive"></i> Solicitações arquivadas</h3>

<?php if($archivedRequests !== FALSE){ ?>

		<div class="box-body table-responsive no-padding">
		<table class="table table-bordered table-hover">
			<tbody>
				<tr>
			        <th class="text-center">Código</th>
			        <th class="text-center">Matrícula</th>
			        <th class="text-center">Status</th>
			        <th class="text-center">Dados adicionais</th>
			        <th class="text-center">Ações</th>
			    </tr>
<?php
					foreach($archivedRequests as $request){

						echo "<tr>";
				    		echo "<td>";
				    		echo $request['id_request'];
				    		echo "</td>";

				    		echo "<td>";
					    		$docConstants = new DocumentConstants();
					    		$allTypes = $docConstants->getAllTypes();
					    		echo $allTypes[$request['document_type']];
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
		<h4>Nenhuma solicitação de documentos arquivada pelo aluno.</h4>
	</div>
<?php }?>

<?= anchor("student/documentrequestStudent/requestDocument/{$courseId}/{$studentId}", 'Voltar', "class='btn btn-danger'")?>
