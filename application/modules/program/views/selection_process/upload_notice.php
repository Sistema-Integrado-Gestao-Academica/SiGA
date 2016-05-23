<?php 
	echo form_open_multipart("program/selectiveprocess/saveNoticeFile");

	$hidden = array(
		"course" => $process->getCourse(),
		"selection_process_id" => base64_encode($process->getId())
	);

	echo form_hidden($hidden);

	$noticeFile = array(
		"name" => "notice_file",
		"id" => "notice_file",
		"type" => "file"
	);
	
	$submitFileBtn = array(
		"id" => "open_selective_process_btn",
		"class" => "btn btn-success btn-flat",
		"content" => "Salvar arquivo",
		"type" => "submit",
		"style" => "margin-top: 5%;"
	);
?>

<!-- Notice file upload -->

<h2 class="principal">Submeter edital do processo <b><?= $process->getName(); ?></b></h2>

<?php include("_upload_notice_file.php"); ?>

<?= form_close(); ?>
<br>