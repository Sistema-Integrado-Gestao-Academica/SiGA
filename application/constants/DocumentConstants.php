<?php

require_once('constants.php');

class DocumentConstants extends Constants{
	
	// Document request status
	const REQUEST_OPEN = "open";
	const REQUEST_READY = "ready";

	// Doc names
	const QUALIFICATION_JURY_NAME = "Solicitação de Banca de Qualificação";
	const DEFENSE_JURY_NAME = "Solicitação de Banca de Defesa";
	const PASSAGE_SOLICITATION_NAME = "Solicitação de Passagem";
	const TRANSCRIPT_NAME = "Histórico Escolar";
	const TRANSFER_DOCS_NAME = "Documentos para Transferência";
	const SCHEDULE_NAME = "Grade Horária";
	const OTHER_DOCS_NAME = "Outro";

	// Docs ids
	const QUALIFICATION_JURY = 1;
	const DEFENSE_JURY = 2;
	const PASSAGE_SOLICITATION = 3;
	const TRANSCRIPT = 4;
	const TRANSFER_DOCS = 5;
	const SCHEDULE = 6;
	const OTHER_DOCS = 7;

	private $documentTypes = array(
		self::QUALIFICATION_JURY => self::QUALIFICATION_JURY_NAME,
		self::DEFENSE_JURY => self::DEFENSE_JURY_NAME,
		self::PASSAGE_SOLICITATION => self::PASSAGE_SOLICITATION_NAME,
		self::TRANSCRIPT => self::TRANSCRIPT_NAME,
		self::TRANSFER_DOCS => self::TRANSFER_DOCS_NAME,
		self::SCHEDULE => self::SCHEDULE_NAME,
		self::OTHER_DOCS => self::OTHER_DOCS_NAME
	);

	public function getAllTypes(){
		return $this->documentTypes;
	}
}