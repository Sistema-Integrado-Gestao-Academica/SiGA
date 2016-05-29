<?php

require_once(MODULESPATH."/student/controllers/DocumentrequestStudent.php");
require_once(MODULESPATH."/secretary/controllers/Documentrequest.php");

class DocumentConstants{

	const REQUEST_ARCHIVED = 1;
	const REQUEST_NON_ARCHIVED = 0;

	const DECLARATION = 1;
	const NON_DECLARATION = 0;

	const ANSWERED = 1;
	const NOT_ANSWERED = 0;

	// Document request status
	const REQUEST_OPEN = "open";
	const REQUEST_READY = "ready";
	const REQUEST_READY_ONLINE = "ready online";

	// Non declaration Docs ids
	const QUALIFICATION_JURY = 1;
	const DEFENSE_JURY = 2;
	const PASSAGE_SOLICITATION = 3;
	const TRANSFER_DOCS = 4;
	const DECLARATIONS = 5;
	const OTHER_DOCS = 18;

	// Declaration Docs ids
	const REGULAR_STUDENT_DECLARATION = 6;
	const TGM_DECLARATION = 7;
	const PROBABLE_GRADUATING_DECLARATION = 8;
	const DISCIPLINE_ENROLLMENT_DECLARATION = 9;
	const SCHEDULE_DECLARATION = 10;
	const GRADUATED_DECLARATION = 11;
	const ACADEMIC_BEHAVIOR_REGULAR_STUDENT_DECLARATION = 12;
	const ACADEMIC_BEHAVIOR_EX_STUDENT_DECLARATION = 13;
	const MONITORING_DECLARATION = 14;
	const COURSE_PERIOD_DECLARATION = 15;
	const DONE_DISCISPLINES_DECLARATION = 16;
	const PERIOD_GRADUATED_DECLARATION = 17;


	private $nonDeclarationTypes;
	private $declarationTypes;

	public function __construct(){
		$this->setDeclarationTypes();
		$this->setNonDeclarationTypes();
	}

	private function setDeclarationTypes(){

		$docRequest = new DocumentRequest();
		$types = $docRequest->allDeclarationTypes();

		if($types !== FALSE){
			foreach($types as $type){
				$declarationTypes[$type['id_type']] = $type['document_type'];
			}
		}else{
			$declarationTypes = FALSE;
		}

		$this->declarationTypes = $declarationTypes;
	}

	private function setNonDeclarationTypes(){

		$docRequest = new DocumentRequest();
		$types = $docRequest->allNonDeclarationTypes();

		if($types !== FALSE){
			foreach($types as $type){
				$nonDeclarationTypes[$type['id_type']] = $type['document_type'];
			}
		}else{
			$nonDeclarationTypes = FALSE;
		}

		$this->nonDeclarationTypes = $nonDeclarationTypes;
	}

	public function getDeclarationTypes(){
		return $this->declarationTypes;
	}

	public function getNonDeclarationTypes(){
		return $this->nonDeclarationTypes;
	}

	public function getAllTypes(){

		$declarationTypes = $this->declarationTypes;
		$nonDeclarationTypes = $this->nonDeclarationTypes;

		if($declarationTypes !== FALSE && $nonDeclarationTypes !== FALSE){
			$allTypes = $nonDeclarationTypes + $declarationTypes;
		}else{
			$allTypes = FALSE;
		}

		return $allTypes;
	}
}