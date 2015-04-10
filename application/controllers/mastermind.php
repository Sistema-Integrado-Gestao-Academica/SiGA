<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('semester.php');
require_once('request.php');

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
		define("STUDENT", 7);
		define("TEACHERS", 5);
		$this->load->model('usuarios_model');
	
		$students = $this->usuarios_model->getUsersOfGroup(STUDENT);
		$masterminds = $this->usuarios_model->getUsersOfGroup(TEACHERS);
	
		$courseStudents = $this->getCourseStudents($students, $courseId);
		$courseStudentsToForm = $this->changeStudentsArrayToFormMode($courseStudents);
		$mastermindsToForm = $this->changeMasterMindsArrayToFormMode($masterminds);
	
		$courseData = array(
				'students' => $courseStudentsToForm,
				'masterminds' => $mastermindsToForm,
				'course_id' => $courseId
		);
	
		loadTemplateSafelyByPermission("cursos",'mastermind/enroll_mastermind_to_student.php', $courseData);
	}
	
	
	public function displayMastermindPage($courseId){
		$this->load->model('mastermind_model');
		$existingRelations = $this->mastermind_model->getMastermindStudentRelations();
		if($existingRelations){
			$relationsToTable = $this->getMasterminsAndStudentNames($existingRelations);
		}else{
			$relationsToTable = FALSE;
		}
		$data = array(
				'relationsToTable' => $relationsToTable,
				'courseId' => $courseId
		);
		loadTemplateSafelyByPermission("cursos",'mastermind/check_mastermind.php',$data);
	}
	
	public function displayMastermindStudents(){
		
		$session = $this->session->userdata("current_user");
		
		$this->load->model('semester_model');
		$currentSemester = $this->semester_model->getCurrentSemester();
		
		$this->load->model('mastermind_model');

		$students = $this->mastermind_model->getStutentsByIdMastermind($session['user']['id']);
		
		$studentsRequests = $this->getStudentsRequests($students,$currentSemester['id_semester']);
		
		$requestData = array('requests' => $studentsRequests);
				
		loadTemplateSafelyByPermission("mastermind", 'mastermind/display_mastermind_students', $requestData);
	}
	
	public function finalizeRequest($requestId){

		$request = new Request();
		
		$wasFinalized = $request->finalizeRequestToMastermind($requestId);

		if($wasFinalized){
			$status = "success";
			$message = "Solicitação finalizada com sucesso.";
		}else{
			$status = "danger";
			$message = "A solicitação não pôde ser finalizada.";
		}

		$this->session->set_flashdata($status, $message);
		redirect('mastermind');
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
	
	private function changeStudentsArrayToFormMode($students){
		$studentsTorecursiveArray = array();
		$studentsTorecursiveArray = array_merge_recursive($studentsTorecursiveArray,  $students);
		
		$limit = sizeof($studentsTorecursiveArray);
		
		for ($i=0 ; $i < $limit; $i++){
			$studentsToForm[$studentsTorecursiveArray[$i]['id']] = ucfirst($studentsTorecursiveArray[$i]['name']);
		}
		
		return $studentsToForm;
	}
	
	private function changeMasterMindsArrayToFormMode($masterminds){
		$mastermindsToRecursiveArray = array();
		$mastermindsToRecursiveArray = array_merge_recursive($mastermindsToRecursiveArray,  $masterminds);
	
		$limit = sizeof($mastermindsToRecursiveArray);
	
		for ($i=0 ; $i < $limit; $i++){
			$mastermindsToForm[$mastermindsToRecursiveArray[$i]['id']] = ucfirst($mastermindsToRecursiveArray[$i]['name']);
		}
	
		return $mastermindsToForm;
	}
	
}
