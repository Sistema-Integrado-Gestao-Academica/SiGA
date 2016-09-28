<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/GroupConstants.php");

class SecretaryAjax extends MX_Controller {

	public function searchStudentsByEnrollment(){

		$enrollment = $this->input->post("enrollment");
		$course = $this->input->post("course");

		$this->load->model("student/student_model");
		$studentsIds = $this->student_model->getUserByEnrollment($enrollment, TRUE);
		
		echo "<h3><i class='fa fa-users'></i> Alunos com a matrícula '{$enrollment}':</h3><br>";
		$this->searchStudentsByIds($studentsIds, $course, TRUE);

	}

	public function searchStudentsByName(){
	   
		$name = $this->input->post("name");
		$course = $this->input->post("course");

		$this->load->model("student/student_model");
		$studentsIds = $this->student_model->getStudentByName($name);
		
		echo "<h3><i class='fa fa-users'></i> Alunos com o nome '{$name}':</h3><br>";
		$this->searchStudentsByIds($studentsIds, $course);
	}

	private function searchStudentsByIds($studentsIds, $courseId, $idIsEnrollment = FALSE){
	   
		$students = array();
		if($studentsIds !== FALSE){

			foreach ($studentsIds as $studentId) {
				$id = $studentId['id'];
				$student = $this->student_model->getStudentById($id, $courseId);
				if($student !== FALSE){
					if($idIsEnrollment){
						$key = $student[0]['enrollment'];
					}
					else{
						$key = $student[0]['name'];
					}
					$students[$key] = $student[0];                        
				}
			}
			$this->load->module("program/course");
			$students = $this->course->addStatusCourseStudents($students);
		}

		if(!empty($students)){
			ksort($students);
			$studentsIdsInString = $this->getStudentsIdsOnString($students, $courseId);
			displayStudentsTable($students, $courseId, $studentsIdsInString);
		}
		else{
			echo callout("info", "Nenhum aluno encontrado");
		}
	}

	function orderStudentsOnList(){

		$studentsIds = $this->input->post("studentsIds");
		$courseId = $this->input->post("courseId");
		$type = $this->input->post("type");

		$students = array();
		if($studentsIds !== FALSE){

			$this->load->model("student/student_model");
			foreach ($studentsIds as $id) {
				$student = $this->student_model->getStudentById($id, $courseId);
				if($student !== FALSE){
					$key = $student[0][$type];
					$students[strtolower($key)] = $student[0];
				}
			}
			$this->load->module("program/course");
			$students = $this->course->addStatusCourseStudents($students);
			ksort($students);
		}

		$studentsIdsInString = implode(",", $studentsIds);
    	$studentsIdsInString = $courseId.",".$studentsIdsInString;
		displayStudentsTable($students, $courseId, $studentsIdsInString);
	}

	private function getStudentsIdsOnString($students, $courseId){
		$studentsIdsInString = "";
		if($students !== FALSE){
			$ids = array();
			foreach ($students as $student) {
				$id = $student['id'];
				array_push($ids, $id);
			}
    		$studentsIdsInString = implode(",", $ids);
    		$studentsIdsInString = $courseId.",".$studentsIdsInString;
		}

		return $studentsIdsInString;
	}
}
