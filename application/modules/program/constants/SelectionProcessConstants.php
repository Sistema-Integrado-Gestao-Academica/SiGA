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

	// Status
	const DRAFT = "draft";
	const DISCLOSED = "disclosed";
	const OPEN_FOR_SUBSCRIPTIONS = "subscriptions";
	const FINISHED = "finished";
	const INCOMPLETE_CONFIG = "incomplete_config";
	const WAITING_NEXT_PHASE = "waiting";
	const IN_HOMOLOGATION_PHASE = "homologation_phase";
	const IN_PRE_PROJECT_PHASE = "pre_project_phase";
	const IN_WRITTEN_TEST_PHASE = "written_test_phase";
	const IN_ORAL_TEST_PHASE = "oral_test_phase";
	const APPEAL_PHASE = "appeal_phase";
}


