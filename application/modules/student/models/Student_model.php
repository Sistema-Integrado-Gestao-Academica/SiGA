<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH."/exception/StudentRegistrationException.php");
require_once(MODULESPATH."/student/constants/StatusConstants.php");

class Student_model extends CI_Model {

	public function getNameAndEnrollment($studentId){

		$this->db->select("users.name, course_student.enrollment");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("users.id", $studentId);
		$student = $this->db->get()->result_array();

		$student = checkArray($student);

		return $student;
	}

	public function getUserByEnrollment($enrollment, $partOfEnrollment = FALSE){

		$this->db->select("users.id");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		if($partOfEnrollment){
			$this->db->like("course_student.enrollment", $enrollment, "after");
		}
		else{

			$this->db->where("course_student.enrollment", $enrollment);
		}
		$student = $this->db->get()->result_array();

		$student = checkArray($student);

		return $student;
	}

	public function getStudentByName($name){

		$this->db->select("users.id");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->like("users.name", $name, "after");
		$student = $this->db->get()->result_array();

		$student = checkArray($student);

		return $student;
	}

	public function getBasicInfo($studentId){
		
		$this->db->select("users.email, users.home_phone, users.cell_phone, course_student.enrollment, course_student.id_course");
		$this->db->from("users");
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.id_user", $studentId);
		$basicInfo = $this->db->get()->result_array();
		
		$basicInfo = checkArray($basicInfo);

		return $basicInfo;
	}

	public function updateBasicInfo($basicInfo){

		$userId = $basicInfo['id_user'];
		$homePhone = $basicInfo['home_phone'];
		$cellPhone = $basicInfo['cell_phone'];

		$newInfo = array(
			'home_phone' => $homePhone->getNumber(),
			'cell_phone' => $cellPhone->getNumber()
		);

		$this->db->trans_start();

		$this->db->where("id", $userId);
		$this->db->update("users", $newInfo);

		$this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $updated = FALSE;
        }else{
        	$updated = TRUE;
        }

        return $updated;
	}

	public function getStudentSemesterAndCourseDuration($studentId){
		$this->db->select("course.duration, course_student.enroll_semester");
		$this->db->from('course');
		$this->db->join("course_student", "course_student.id_course = course.id_course");
		$this->db->where("course_student.id_user", $studentId);

		$student = $this->db->get()->result_array();
		
		$student = checkArray($student);

		return $student[0];
	}

	public function getStudentById($studentId, $courseId = FALSE){

		$this->db->select("users.name, users.id, users.email, course_student.enroll_date, course_student.enrollment");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");
		$this->db->where("course_student.id_user", $studentId);

		if($courseId !== FALSE){
			$this->db->where("course_student.id_course", $courseId);
		}
		$student = $this->db->get()->result_array();
		
		$student = checkArray($student);

		return $student;
	}

	public function getAllStudents(){
		
		$this->db->select("users.id");
		$this->db->from('users');
		$this->db->join("course_student", "course_student.id_user = users.id");

		$students = $this->db->get()->result_array();
		
		$students = checkArray($students);

		return $students;
	}

	public function setDelayedQualifyStatus($studentId){

		$data = array(
			'user_id' => $studentId,
			'description' =>  StatusConstants::DELAYED_QUALIFY,
			'label_type' => StatusConstants::LABEL_DANGER_TYPE
		);

		$this->db->insert('student_status', $data);
	}

	public function getStudentStatus($studentId){
		$this->db->select("student_status.description, student_status.label_type");
		$this->db->from('student_status');
		$this->db->where("student_status.user_id", $studentId);

		$studentStatus = $this->db->get()->result_array();
		
		$studentStatus = checkArray($studentStatus);

		return $studentStatus;
	}
}