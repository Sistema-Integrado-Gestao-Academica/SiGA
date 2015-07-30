<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('course.php');
require_once('semester.php');
require_once('request.php');
require_once(APPPATH."/constants/GroupConstants.php");
require_once(APPPATH."/constants/PermissionConstants.php");

class MasterMind extends CI_Controller {
	
	public function saveMastermindToStudent(){
		
		$student = $this->input->post('course_student');
		$mastermind = $this->input->post('student_mastermind');
		$courseId = $this->input->post('courseId');
		
		$saveRelation = array(
				'id_student' => $student,
				'id_mastermind' => $mastermind
				
		);
		
		$this->load->model('mastermind_model');
		$savedMastermindStudent = $this->mastermind_model->relateMastermindToStudent($saveRelation);
		
		
		if($savedMastermindStudent){
			$updateStatus = "success";
			$updateMessage = "Relação entre orientador e aluno salva com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível salvar a relação entre orientador e aluno. Tente novamente.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect("mastermind/displayMastermindPage/$courseId");
		
	}
	
	public function deleteMastermindStudentRelation($mastermindId, $studentId,$courseId){
		$lineToRemove = array(
				'id_mastermind' => $mastermindId,
				'id_student'    => $studentId
		);
		
		$this->load->model('mastermind_model');
		$removed = $this->mastermind_model->removeMastermindStudentRelation($lineToRemove);
		
		if($removed){
			$updateStatus = "success";
			$updateMessage = "Relação entre orientador e aluno deletada com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível deletar a relação entre orientador e aluno. Tente novamente.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect("mastermind/displayMastermindPage/$courseId");
	}
	
	public function enrollMastermindToStudent($courseId){
		
		$this->load->model('usuarios_model');

		$course = new Course();
		$courseStudents = $course->getCourseStudents($courseId);

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
	
		loadTemplateSafelyByPermission("cursos",'mastermind/enroll_mastermind_to_student.php', $courseData);
	}
		
	public function displayMastermindPage($courseId){
		
		$this->load->model('mastermind_model');
		
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

		loadTemplateSafelyByPermission("cursos", 'mastermind/check_mastermind.php', $data);
	}
	
	public function index(){
		
		loadTemplateSafelyByPermission(PermissionConstants::MASTERMIND_PERMISSION, 'mastermind/index');
	}
	
	public function titlingArea(){
		
		$this->load->model("program_model");
		
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
		
		loadTemplateSafelyByPermission("mastermind", "mastermind/titling.php", $data);
		
		
	}
	
	public function titlingAreaUpdateBySecretary($mastermindId){
		$this->load->model("program_model");
		
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
		
		loadTemplateSafelyByGroup("courseSecretaryAcademic", "mastermind/titling.php", $data);
		
	}
	
	private function getMastermindTitlingArea($mastermindId=NULL){
		if($mastermindId){
			$userId = $mastermindId;
		}else{
			$session = $this->session->userdata("current_user");
			$userId = $session['user']['id'];
		}
		
		$this->load->model("mastermind_model");
		
		$currentArea = $this->mastermind_model->getCurrentArea($userId);
		
		return $currentArea;
	}
	
	public function UpdateTitlingArea(){
		$session = $this->session->userdata("current_user");
		$userId = $session['user']['id'];
		
		$titlingArea = $this->input->post("titling_area");
		$tiling_thesis = $this->input->post("titling_thesis");
		
		$this->load->model("mastermind_model");
		
		$updated = $this->mastermind_model->updateTitlingArea($userId, $titlingArea, $tiling_thesis);
		
		if($updated){
			$updateStatus = "success";
			$updateMessage = "Titulação alterada com sucesso";
		}else{
			$updateStatus = "danger";
			$updateMessage = "Não foi possível alterar sua titulação. Tente novamente.";
		}
		
		$this->session->set_flashdata($updateStatus, $updateMessage);
		redirect('/');
		
	}
	
	public function displayMastermindStudents(){
		
		$session = $this->session->userdata("current_user");
		
		$this->load->model('semester_model');
		$currentSemester = $this->semester_model->getCurrentSemester();
		
		$this->load->model('mastermind_model');

		$students = $this->mastermind_model->getStutentsByIdMastermind($session['user']['id']);
		
		$studentsRequests = $this->getStudentsRequests($students,$currentSemester['id_semester']);
		
		$requestData = array('requests' => $studentsRequests, 'idMastermind' => $session['user']['id']);
				
		loadTemplateSafelyByPermission("mastermind", 'mastermind/display_mastermind_students', $requestData);
	}

	public function getMastermindMessage($mastermindId, $requestId){

		$this->load->model('mastermind_model');

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

		$request = new Request();
		$wasFinalized = $request->finalizeRequestToMastermind($requestId);

		if($wasFinalized){
			
			$messageSaved = $request->saveMastermindMessage($mastermindId, $requestId, $mastermindMessage);
			
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

		$this->session->set_flashdata($status, $message);
		redirect('mastermind');
	}

	public function getMastermindByStudent($studentId){

		$this->load->model('mastermind_model');

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
		$this->load->model('request_model');
		
		for ($i=0; $i<$stutendArraySize; $i++){
			$studentsRequests[$i] = $this->request_model->getMastermindStudentRequest($students[$i]['id_student'], $currentSemester);
		}
		
		return $studentsRequests;
		
	}
	
	private function getMasterminsAndStudentNames($existingRelations){
		$this->load->model('usuarios_model');
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
		$this->load->model('usuarios_model');
		
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
