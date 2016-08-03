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

function prettyReceiveOption($receiveOption){

	// This means that the document can be provided online
	$option = "<b>Entrega do documento</b>: ";
	if($receiveOption){
		$option .=  "<span class='label label-primary'>Online</span>";
	}else{
		$option .=  "<span class='label label-default'>Em mãos</span>";
	}

	return $option;
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

function prettyDisciplineRestrict($restrict){

	$status = "";
	if($restrict){
		$status .= "<h4><span class='label label-default'>Restrita do curso</span></h4>";
	}else{
		$status .= "<h4><span class='label label-primary'>Livre para o programa</span></h4>";
	}

	return $status;
}

function prettyRequestDate($request){
	$msg = "Solicitado em <b>";
	$msg .= $request['requested_on'] != NULL ? "{$request['requested_on']}" : "-";
	$msg .= "</b>";

	return $msg;
}