<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MasterMind extends CI_Controller {
	
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
		redirect("usuario/secretary_enrollMasterMinds");
		
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
