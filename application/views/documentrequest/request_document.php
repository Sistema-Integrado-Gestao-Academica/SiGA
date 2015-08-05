<h2 class="principal">Solicitação de Documentos</h2>

<?= form_open('documentrequest/newDocumentRequest') ?>
	
	<?= form_hidden("courseId", $courseId)?>
	<?= form_hidden("studentId", $userId)?>
	
	<div class='form-group'>
		<?= form_label("Escolha o tipo de documento:", "documentTypes") ?>
		<?= form_dropdown("documentType", $documentTypes, '', "id='documentType' class='form-control' style='width:40%;'"); ?>
	</div>

	<br>
	<br>
	<div id="document_request_data"></div>

<?= form_close() ?>