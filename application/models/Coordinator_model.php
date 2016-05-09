<?php
require_once(APPPATH."/exception/CourseNameException.php");
class Coordinator_model extends CI_Model {

	public function getTotalCourseStudents($idCoordinator){
		
		$idCourse = $this->getCoordinatorCourse($idCoordinator);
		
		$students = $this->getCourseStudents($idCourse);
		
		return $students;
	}
	
	public function getTotalEnroledStudents($idCoordinator){
		
		$idCourse = $this->getCoordinatorCourse($idCoordinator);
		
		$students = $this->getEnroledStudents($idCourse);
		
		return $students;
	}
	
	public function getTotalNotEnroledStudents($idCoordinator){
	
		$idCourse = $this->getCoordinatorCourse($idCoordinator);
	
		$students = $this->getNotEnroledStudents($idCourse);
	
		return $students;
	}
	
	public function getTotalCourseMasterminds($idCoordinator){
		
		$idCourse = $this->getCoordinatorCourse($idCoordinator);
		
		$masterminds = $this->getCourseMasterminds($idCourse);
		
		return $masterminds;
	}
	

	public function getCoordinatorCourse($idCoordinator){
	
		$program = $this->db->get_where('program',array('coordinator'=>$idCoordinator))->row_array();
	
		$course = $this->db->get_where('course',array('id_program'=>$program['id_program']))->row_array();
	
		$course = checkArray($course);
	
		return $course['id_course'];
	}
	
	
	private function getCourseMasterminds($idCourse){
		
		$masterminds = $this->db->get_where('teacher_course', array('id_course'=>$idCourse))->result_array();
		
		$masterminds = checkArray($masterminds);
		
		return $masterminds;
	}
	
	private function getNotEnroledStudents($idCourse){
		$this->db->where('id_course', $idCourse);
		$this->db->where('request_status !=', "all_approved");
		$notEnroled = $this->db->get('student_request')->result_array();
		
		$notEnroled = checkArray($notEnroled);
		
		$quantity = sizeof($notEnroled);
		
		return $quantity;
	}
	
	private function getEnroledStudents($idCourse){
		$enroled = $this->db->get_where('student_request',array('id_course'=>$idCourse, 'request_status'=>"all_approved"))->result_array();
		
		$enroled = checkArray($enroled);
		
		$quantity = sizeof($enroled);
		
		return $quantity;
	}
	
	private function getCourseStudents($idCourse){
		
		$students = $this->db->get_where('course_student',array('id_course'=>$idCourse))->result_array();
		$students = checkArray($students);
		
		$quantity = sizeof($students);
		
		return $quantity;
	}

	public function getCoordinatorsForHomepage($programs){

		$coordinators = array();

		$this->load->model('teacher_model');

		if($programs !== FALSE){
			
			foreach ($programs as $program) {
				$coordinatorId = $program['coordinator'];
				$coordinators[$program['id_program']]['extra_data'] = $this->teacher_model->getInfoTeacherForHomepage($coordinatorId);
				$coordinators[$program['id_program']]['basic_data'] = $this->teacher_model->getTeacherData($coordinatorId);
				
			}
		}

		return $coordinators;
	}
}