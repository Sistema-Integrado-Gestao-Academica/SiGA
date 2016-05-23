<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");
require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class MasterMind extends MX_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('program/mastermind_model');
	}

	public function saveMastermindToStudent(){

		$student = $this->input->post('course_student');
		$mastermind = $this->input->post('student_mastermind');
		$courseId = $this->input->post('courseId');

		$saveRelation = array(
				'id_student' => $student,
				'id_mastermind' => $mastermind

		);

		$savedMastermindStudent = $this->mastermind_model->relateMastermindToStudent($saveRelation);


		if($savedMastermindStudent){
			$updateStatus = "success";
			$updateMessage = "Relação entre orientador e aluno salva com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível salvar a relação entre orientador e aluno. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($updateStatus, $updateMessage);
		redirect("program/mastermind/displayMastermindPage/$courseId");

	}

	public function deleteMastermindStudentRelation($mastermindId, $studentId,$courseId){
		$lineToRemove = array(
				'id_mastermind' => $mastermindId,
				'id_student'    => $studentId
		);

		$removed = $this->mastermind_model->removeMastermindStudentRelation($lineToRemove);

		if($removed){
			$updateStatus = "success";
			$updateMessage = "Relação entre orientador e aluno deletada com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível deletar a relação entre orientador e aluno. Tente novamente.";
		}

		$session = getSession();
		$session->showFlashMessage($updateStatus, $updateMessage);
		redirect("program/mastermind/displayMastermindPage/$courseId");
	}

	public function enrollMastermindToStudent($courseId){

		$this->load->model('auth/usuarios_model');

		$this->load->model("program/course_model");
		$courseStudents = $this->course_model->getCourseStudents($courseId);

		if($courseStudents !== FALSE){

			foreach($courseStudents as $student){
				$courseStudentsToForm[$student['id']] = ucfirst($student['name']);
			}
		}else{
			$courseStudentsToForm = FALSE;
		}

		$masterminds = $this->usuarios_model->getUsersOfGroup(GroupConstants::TEACHER_GROUP_ID);

		if($masterminds !== FALSE){

			foreach($masterminds as $mastermind){
				$mastermindsToForm[$mastermind['id']] = $mastermind['name'];
			}
		}else{
			$mastermindsToForm = FALSE;
		}

		$courseData = array(
			'students' => $courseStudentsToForm,
			'masterminds' => $mastermindsToForm,
			'courseId' => $courseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION,'program/mastermind/enroll_mastermind_to_student.php', $courseData);
	}

	public function displayMastermindPage($courseId){


		$existingRelations = $this->mastermind_model->getMastermindStudentRelations();

		if($existingRelations !== FALSE){
			$relationsToTable = $this->getMasterminsAndStudentNames($existingRelations);
		}else{
			$relationsToTable = FALSE;
		}

		$data = array(
				'relationsToTable' => $relationsToTable,
				'courseId' => $courseId
		);

		loadTemplateSafelyByPermission(PermissionConstants::COURSES_PERMISSION, 'program/mastermind/check_mastermind.php', $data);
	}

	public function index(){

		loadTemplateSafelyByPermission(PermissionConstants::MASTERMIND_PERMISSION, 'program/mastermind/index');
	}

	public function titlingArea(){

		$this->load->model("program/program_model");

		$areas = $this->program_model->getAllProgramAreas();

		if($areas !== FALSE){
			foreach ($areas as $area){

				$areasResult[$area['id_program_area']] = $area['area_name'];
			}
		}else{
			$areasResult = FALSE;
		}
		$areasResult = array_merge(array(0=>"Escolha uma área"),$areasResult);

		$mastermindTitlingArea = $this->getMastermindTitlingArea();

		$data = array('areas' => $areasResult, 'currentArea' => $mastermindTitlingArea);

		loadTemplateSafelyByPermission(PermissionConstants::MASTERMIND_PERMISSION, "program/mastermind/titling.php", $data);


	}

	public function titlingAreaUpdateBySecretary($mastermindId){

		$this->load->model("program/program_model");
		$areas = $this->program_model->getAllProgramAreas();
		if($areas !== FALSE){
			foreach ($areas as $area){

				$areasResult[$area['id_program_area']] = $area['area_name'];
			}
		}else{
			$areasResult = FALSE;
		}
		$areasResult = array_merge(array(0=>"Escolha uma área"),$areasResult);

		$mastermindTitlingArea = $this->getMastermindTitlingArea($mastermindId);

		$data = array('areas' => $areasResult, 'currentArea' => $mastermindTitlingArea);

		loadTemplateSafelyByGroup(GroupConstants::ACADEMIC_SECRETARY_GROUP, "program/mastermind/titling.php", $data);

	}

	private function getMastermindTitlingArea($mastermindId=NULL){


		if($mastermindId){
			$userId = $mastermindId;
		}else{
			$session = getSession();
			$user = $session->getUserData();
			$userId = $user->getId();
		}

		$currentArea = $this->mastermind_model->getCurrentArea($userId);

		return $currentArea;
	}

	public function UpdateTitlingArea(){

		$session = getSession();
		$user = $session->getUserData();
		$userId = $user->getId();

		$titlingArea = $this->input->post("titling_area");
		$tiling_thesis = $this->input->post("titling_thesis");

		$updated = $this->mastermind_model->updateTitlingArea($userId, $titlingArea, $tiling_thesis);

		if($updated){
			$updateStatus = "success";
			$updateMessage = "Titulação alterada com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível alterar sua titulação. Tente novamente.";
		}

		$session->showFlashMessage($updateStatus, $updateMessage);
		redirect('mastermind_home');

	}

	public function displayMastermindStudents(){

		$this->load->model('program/semester_model');
		$this->load->model('secretary/request_model');

		$session = getSession();
		$user = $session->getUserData();

		$currentSemester = $this->semester_model->getCurrentSemester();

		$userId = $user->getId();
		$students = $this->mastermind_model->getStutentsByIdMastermind($userId);

		$studentsRequests = $this->getStudentsRequests($students,$currentSemester['id_semester']);

		$requestData = array(
			'requests' => $studentsRequests,
			'idMastermind' => $user->getId()
		);

		loadTemplateSafelyByPermission(PermissionConstants::MASTERMIND_PERMISSION, 'program/mastermind/display_mastermind_students', $requestData);
	}

	public function getMastermindMessage($mastermindId, $requestId){

		$foundMessage = $this->mastermind_model->getMastermindMessage($mastermindId, $requestId);

		if($foundMessage !== FALSE){
			if($foundMessage['message'] !== NULL){
				$message = $foundMessage['message'];
			}else{
				$message = FALSE;
			}
		}else{
			$message = FALSE;
		}

		return $message;
	}

	public function finalizeRequest(){

		$requestId = $this->input->post('requestId');
		$mastermindId = $this->input->post('mastermindId');
		$mastermindMessage = $this->input->post('mastermind_message');

		$this->load->model("secretary/request_model");
		$wasFinalized = $this->request_model->finalizeRequestToMastermind($requestId);

		if($wasFinalized){

			$messageSaved = $this->request_model->saveMastermindMessage($mastermindId, $requestId, $mastermindMessage);

			if($messageSaved){

				$status = "success";
				$message = "Solicitação finalizada e mensagem salva com sucesso.";
			}else{
				$status = "success";
				$message = "Solicitação finalizada com sucesso, mas não foi possível salvar a mensagem.";
			}

		}else{
			$status = "danger";
			$message = "A solicitação não pôde ser finalizada.";
		}

		$session = getSession();
		$session->showFlashMessage($status, $message);
		redirect('mastermind');
	}

	public function getMastermindByStudent($studentId){

		$mastermind = $this->mastermind_model->getMastermindByStudent($studentId);

		if($mastermind !== FALSE){
			$mastermind = $mastermind['id_mastermind'];
		}else{
			$mastermind = FALSE;
		}

		return $mastermind;
	}

	private function getStudentsRequests($students, $currentSemester){
		$stutendArraySize = sizeof($students);
		$this->load->model('secretary/request_model');

		for ($i=0; $i<$stutendArraySize; $i++){
			$studentsRequests[$i] = $this->request_model->getMastermindStudentRequest($students[$i]['id_student'], $currentSemester);
		}

		return $studentsRequests;

	}

	private function getMasterminsAndStudentNames($existingRelations){
		$this->load->model('auth/usuarios_model');
		$limit = sizeof($existingRelations);

		for($i=0; $i<$limit; $i++){
			$mastemindName = $this->usuarios_model->getNameByUserId($existingRelations[$i]['id_mastermind']);
			$studentName = $this->usuarios_model->getNameByUserId($existingRelations[$i]['id_student']);
			$relatedMastermindAndStudent[$i]['mastermind_name'] = ucfirst($mastemindName);
			$relatedMastermindAndStudent[$i]['mastermind_id'] = $existingRelations[$i]['id_mastermind'];
			$relatedMastermindAndStudent[$i]['student_name'] = ucfirst($studentName);
			$relatedMastermindAndStudent[$i]['student_id'] = $existingRelations[$i]['id_student'];

		}

		return $relatedMastermindAndStudent;

	}

	private function getCourseStudents($students, $courseId){

		$limit = sizeof($students);
		$this->load->model('auth/usuarios_model');

		for ($cont =0; $cont < $limit; $cont++){

			$idCourseStudent = $this->usuarios_model->getUserCourse($students[$cont]['id']);
			$courseLimit = sizeof($idCourseStudent);
			if ($idCourseStudent != FALSE){

				for ($i=0; $i < $courseLimit; $i++){
					$studentCourseId[$i] = $idCourseStudent[$i]['id_course'];
				}

			}else{
				$studentCourseId = FALSE;
			}

			for ($i=0; $i < $courseLimit; $i++){

				if ($studentCourseId[$i] == $courseId){
					$courseStudent[$cont] = $students[$cont];
				}else{
					//nothing to do
				}

			}

		}

		return $courseStudent;
	}

}
