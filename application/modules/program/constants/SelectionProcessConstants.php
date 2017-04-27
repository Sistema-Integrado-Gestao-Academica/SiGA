<?php

class SelectionProcessConstants {

	const HOMOLOGATION_PHASE = "Homologação";
	const PRE_PROJECT_EVALUATION_PHASE = "Avaliação de Pré-Projeto";
	const WRITTEN_TEST_PHASE = "Prova escrita";
	const ORAL_TEST_PHASE = "Prova oral";
	
	const HOMOLOGATION_PHASE_ID = 1;
	const PRE_PROJECT_EVALUATION_PHASE_ID = 2;
	const WRITTEN_TEST_PHASE_ID = 3;
	const ORAL_TEST_PHASE_ID = 4;

	const HOMOLOGATION_PHASE_WEIGHT = 0;
	const HOMOLOGATION_PHASE_GRADE = 0;
	
	const REGULAR_STUDENT = "regular_student";
	const SPECIAL_STUDENT = "special_student";
	const REGULAR_STUDENT_PORTUGUESE = "Aluno regular";
	const SPECIAL_STUDENT_PORTUGUESE = "Aluno especial";

	const DRAFT = "<p class='label label-warning'> Rascunho </p>";
	const DISCLOSED = "<p class='label label-success'>Divulgado</p>";
	const OPEN_FOR_SUBSCRIPTIONS = "<p class='label label-info'>Inscrições abertas</p>";
	const IN_HOMOLOGATION_PHASE = "<p class='label label-success'>Em fase de Homologação</p>";
	const IN_PRE_PROJECT_PHASE = "<p class='label label-success'>Em fase de Avaliação de Pré-Projeto</p>";
	const IN_WRITTEN_TEST_PHASE = "<p class='label label-success'>Em fase de Prova escrita</p>";
	const IN_ORAL_TEST_PHASE = "<p class='label label-success'>Em fase de Prova Oral</p>";
	const FINISHED = "<p class='label label-danger'>Encerrado</p>";
	const INCOMPLETE_CONFIG = "<p class='label label-danger'>Configuração incompleta</p>";
	const WAITING_NEXT_PHASE = "<p class='label label-warning'> Aguardando próxima fase </p>";

	const PRE_PROJECT_DOCUMENT_ID = 10;
}

