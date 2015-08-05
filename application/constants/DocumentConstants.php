<?php

require_once('constants.php');

class DocumentConstants extends Constants{
	
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
}