<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Syllabus extends MX_Controller {

	public function courseSyllabus($courseId){

		$foundSyllabus = $this->getCourseSyllabus($courseId);

		if($foundSyllabus !== FALSE){
			$syllabusDisciplines = $this->getSyllabusDisciplines($foundSyllabus['id_syllabus']);
		}else{
			$syllabusDisciplines = FALSE;
		}

		$this->load->model("program/course_model");
		$foundCourse = $this->course_model->getCourseById($courseId);

		$data = array(
			'syllabusDisciplines' => $syllabusDisciplines,
			'course' => $foundCourse,
		);

		loadTemplateSafelyByGroup("estudante",'syllabus/course_syllabus_disciplines_student', $data);
	}

	public function secretaryCourseSyllabus(){

		$this->load->model("program/semester_model");
		$currentSemester = $this->semester_model->getCurrentSemester();

		// Get the current user id
		$session = getSession();
		$loggedUserData = $session->getUserData();
		$currentUser = $loggedUserData->getId();

		// Get the courses of the secretary
		$this->load->model("program/course_model");
		$courses = $this->course_model->getCoursesOfSecretary($currentUser);

		if($courses !== FALSE){

			$syllabus = new Syllabus();
			$coursesSyllabus = array();
			foreach ($courses as $course){

				$coursesSyllabus[$course['course_name']] = $syllabus->getCourseSyllabus($course['id_course']);
			}
		}else{
			$coursesSyllabus = FALSE;
		}

		$data = array(
			'current_semester' => $currentSemester,
			'courses' => $courses,
			'syllabus' => $coursesSyllabus,
			'user' => $loggedUserData
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSE_SYLLABUS_PERMISSION,'secretary/secretary_course_syllabus', $data);
	}


	public function getCourseSyllabus($courseId){

		$this->load->model('syllabus_model');

		$courseSyllabus = $this->syllabus_model->getCourseSyllabus($courseId);

		return $courseSyllabus;
	}

	public function getSyllabusCourse($syllabusId){

		$this->load->model('syllabus_model');

		$syllabusCourse = $this->syllabus_model->getSyllabusCourse($syllabusId);

		return $syllabusCourse;
	}

	public function newSyllabus($courseId){

		$this->load->model('syllabus_model');

		$wasSaved = $this->syllabus_model->newSyllabus($courseId);

		if($wasSaved){
			$status = "success";
			$message = "Currículo criado com sucesso. Adicione disciplinas em EDITAR.";
		}else{
			$status = "danger";
			$message = "Não foi possível criar o currículo para o curso informado. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('secretary/syllabus/secretaryCourseSyllabus');
	}

	public function displayDisciplinesOfSyllabus($syllabusId, $courseId){

		$syllabusDisciplines = $this->getSyllabusDisciplines($syllabusId);

		$this->load->model("program/course_model");
		$foundCourse = $this->course_model->getCourseById($courseId);

		$data = array(
			'syllabusDisciplines' => $syllabusDisciplines,
			'syllabusId' => $syllabusId,
			'course' => $foundCourse
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/course_syllabus_disciplines', $data);
	}

	public function addDisciplines($syllabusId, $courseId){

		$this->load->model("program/discipline_model");
		$allDisciplines = $this->discipline_model->listAllDisciplines();

		$data = array(
			'allDisciplines' => $allDisciplines,
			'syllabusId' => $syllabusId,
			'courseId' => $courseId
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/add_syllabus_disciplines', $data);
	}

	public function relateDisciplineToResearchLine($disciplineCode,$syllabusId, $courseId){

		$researchLines = $this->getResearchLines();

		$currentDiscipline = $this->getCurrentDiscipline($disciplineCode);

		$disciplineResearchLinesIds = $this->getDiscipineResearchLinesIds($disciplineCode);

		$disciplineResearchLines = $this->getDiscipineResearchLinesNames($disciplineResearchLinesIds);

		$data = array(
			'researchLines' => $researchLines,
			'discipline' => $currentDiscipline,
			'syllabusId' => $syllabusId,
			'courseId' => $courseId,
			'disciplineResearchLines' => $disciplineResearchLines
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/disciplines_research_line', $data);
	}

	private function getDiscipineResearchLinesIds($disciplineCode){
		$discipline = new Discipline();

		$disciplineResearchLines = $discipline->getDisciplineResearchLines($disciplineCode);

		return $disciplineResearchLines;
	}

	public function getDiscipineResearchLinesNames($disciplineResearchLinesIds){

		$course = new Course();

		if($disciplineResearchLinesIds !== FALSE){
			foreach ($disciplineResearchLinesIds as $quantity => $researchLines){

				$disciplinesResearchLines[$researchLines['id_research_line']] = $course->getResearchLineNameById($researchLines['id_research_line']);
			}
		}else{
			$disciplinesResearchLines = FALSE;
		}

		return $disciplinesResearchLines;
	}

	private function getCurrentDiscipline($disciplineCode){
		$discipline = new Discipline();

		$currentDiscipline = $discipline->getDisciplineByCode($disciplineCode);

		return $currentDiscipline;
	}

	private function getResearchLines(){
		$this->load->model("course_model");

		$researchLines = $this->course_model->getAllResearchLines();

		$research[0] = "Escolha uma Linha de Pesquisa";
		if($researchLines !== FALSE){
			foreach ($researchLines as $key => $lines){
				$research[$lines['id_research_line']] = $lines['description'];
			}
		}else{
			$research[0] = "Nenhuma Linha de Pesquisa cadastrada";
		}

		return $research;
	}

	public function saveDisciplineResearchLine(){
		define("NO_RESEARCH_LINE_CHOICE", 0);

		$disciplineCode = $this->input->post('discipline_code');
		$researchLineId = $this->input->post('research_line');
		$courseId = $this->input->post('courseId');
		$syllabusId = $this->input->post('syllabusId');

		$session = getSession();
		if ($researchLineId == NO_RESEARCH_LINE_CHOICE){
			$status = "danger";
			$message = "Não foi escolhida nenhuma linha de pesquisa";
			$session->showFlashMessage($status, $message);
			redirect("secretary/syllabus/relateDisciplineToResearchLine/{$disciplineCode}/{$syllabusId}/{$courseId}");

		}else{

			$saveData = array(
				'discipline_code' => $disciplineCode,
				'id_research_line' => $researchLineId
			);

			$saved = $this->saveDisciplineResearchRelation($saveData);

			if ($saved){
				$status = "success";
				$message = "Disciplina relacionada com sucesso.";
			}else{
				$status = "danger";
				$message = "Disciplina não pode ser relacionada.";
			}

			$session->showFlashMessage($status, $message);
			redirect("secretary/syllabus/relateDisciplineToResearchLine/{$disciplineCode}/{$syllabusId}/{$courseId}");
		}
	}

	public function removeDisciplineResearchLine($researchLineId, $disciplineCode, $syllabusId, $courseId){
		$discipline = new Discipline();

		$researchRelation = array(
				'id_research_line' => $researchLineId,
				'discipline_code' => $disciplineCode
		);

		$deleted = $discipline->deleteDisciplineResearchRelation($researchRelation);
		if ($deleted){
			$status = "success";
			$message = "Disciplina desrelacionada com sucesso.";
		}else{
			$status = "danger";
			$message = "Disciplina não pode ser desrelacionada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/syllabus/relateDisciplineToResearchLine/{$disciplineCode}/{$syllabusId}/{$courseId}");
	}

	private function saveDisciplineResearchRelation($relationToSave){
		$discipline = new Discipline();

		$saved = $discipline->saveDisciplineResearchRelation($relationToSave);

		return $saved;
	}

	public function searchForDiscipline(){

		$searchType = $this->input->post('searchType');
		$courseId = $this->input->post('courseId');
		$syllabusId = $this->input->post('syllabusId');

		define("SEARCH_FOR_DISCIPLINE_ID", "by_id");
		define("SEARCH_FOR_DISCIPLINE_NAME", "by_name");

		$discipline = new Discipline();
		switch($searchType){
			case SEARCH_FOR_DISCIPLINE_ID:
				$disciplineId = $this->input->post('discipline_to_search');
				$foundDiscipline = $discipline->getDisciplineByCode($disciplineId);

				if($foundDiscipline !== FALSE){
					$disciplines[] = $foundDiscipline;
				}else{
					$disciplines = FALSE;
				}
				break;

			case SEARCH_FOR_DISCIPLINE_NAME:
				$disciplineName = $this->input->post('discipline_to_search');
				$disciplines = $discipline->getDisciplineByPartialName($disciplineName);
				break;

			default:
				$disciplines = FALSE;
				break;
		}

		$data = array(
			'allDisciplines' => $disciplines,
			'syllabusId' => $syllabusId,
			'courseId' => $courseId
		);

		loadTemplateSafelyByGroup("secretario",'syllabus/add_syllabus_disciplines', $data);
	}

	public function addDisciplineToSyllabus($syllabusId, $disciplineId, $courseId){

		$this->load->model('syllabus_model');

		$wasSaved = $this->syllabus_model->addDisciplineToSyllabus($syllabusId, $disciplineId);

		if($wasSaved){
			$status = "success";
			$message = "Disciplina adicionada com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível adicionar a disciplina ao currículo informado. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/syllabus/addDisciplines/{$syllabusId}/{$courseId}");
	}

	public function removeDisciplineFromSyllabus($syllabusId, $disciplineId, $courseId){

		$this->load->model('syllabus_model');

		$wasRemoved = $this->syllabus_model->removeDisciplineFromSyllabus($syllabusId, $disciplineId);

		if($wasRemoved){
			$status = "success";
			$message = "Disciplina removida com sucesso.";
		}else{
			$status = "danger";
			$message = "Não foi possível remover a disciplina do currículo informado. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect("secretary/syllabus/addDisciplines/{$syllabusId}/{$courseId}");
	}

	public function disciplineExistsInSyllabus($idDiscipline, $syllabusId){

		$this->load->model('syllabus_model');

		$disciplineExists = $this->syllabus_model->disciplineExistsInSyllabus($idDiscipline, $syllabusId);

		return $disciplineExists;
	}

	private function getSyllabusDisciplines($syllabusId){

		$this->load->model("syllabus_model");

		$foundDisciplines = $this->syllabus_model->getSyllabusDisciplines($syllabusId);

		return $foundDisciplines;
	}

}
