<?php

require_once(MODULESPATH."secretary/constants/DocumentConstants.php");

function prettyDocStatus($status) {

	switch($status){
		case DocumentConstants::REQUEST_OPEN:
			$beautifulStatus = "<span class='label label-info'>".lang($status)."</span>";
			break;
		case DocumentConstants::REQUEST_READY:
			$beautifulStatus = "<span class='label label-success'>".lang($status)."</span>";
			break;
		case DocumentConstants::REQUEST_READY_ONLINE:
			$beautifulStatus = "<span class='label label-info'>".lang($status)."</span>";
			break;
		default:
			$beautifulStatus = "-";
			break;
	}

	return $beautifulStatus;
}

function prettyDocType($type, $docName = ""){

	switch($type){
		case DocumentConstants::OTHER_DOCS:
			$beautifulType = "<b>Documento solicitado: </b>".$docName;
			break;
		
		default:
			$beautifulType = "-";
			break;
	}

	return $beautifulType;
}

function prettyDocDownload($request){

	if($request['status'] === DocumentConstants::REQUEST_READY_ONLINE){

		$requestId = $request['id_request'];
		echo anchor(
			"download_doc/{$requestId}",
			"<i class='fa fa-cloud-download'></i> Baixar documento",
			"class='btn btn-info'"
		);
	}
}